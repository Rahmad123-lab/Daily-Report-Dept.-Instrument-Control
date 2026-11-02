<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TrainingParticipantController extends Controller
{
    /**
     * Register the authenticated user for a training.
     */
    public function store(
        Request $request,
        Training $training
    ): RedirectResponse {
        $result = DB::transaction(function () use ($request, $training) {
            // Lock the training row while checking available quota.
            $lockedTraining = Training::query()
                ->whereKey($training->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($lockedTraining->status, ['completed', 'cancelled'], true)) {
                return 'closed';
            }

            $participant = TrainingParticipant::query()
                ->where('training_id', $lockedTraining->id)
                ->where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->first();

            if (
                $participant !== null
                && $participant->registration_status !== 'cancelled'
            ) {
                return 'exists';
            }

            $activeRegistrations = TrainingParticipant::query()
                ->where('training_id', $lockedTraining->id)
                ->whereIn('registration_status', ['registered', 'approved'])
                ->count();

            if (
                $lockedTraining->quota !== null
                && $activeRegistrations >= $lockedTraining->quota
            ) {
                return 'full';
            }

            $data = [
                'registration_status' => 'registered',
                'attendance_status' => 'not_marked',
                'notes' => null,
            ];

            if ($participant !== null) {
                // Reuse a cancelled registration.
                $participant->update($data);
            } else {
                $lockedTraining->participants()->create([
                    'user_id' => $request->user()->id,
                    ...$data,
                ]);
            }

            return 'success';
        });

        if ($result !== 'success') {
            $messages = [
                'closed' => 'Pendaftaran tidak tersedia karena pelatihan sudah selesai atau dibatalkan.',
                'exists' => 'Kamu sudah terdaftar pada pelatihan ini.',
                'full' => 'Kuota peserta pelatihan sudah penuh.',
            ];

            return back()->with('error', $messages[$result]);
        }

        return redirect()
            ->route('trainings.show', $training)
            ->with('success', 'Pendaftaran pelatihan berhasil.');
    }

    /**
     * Cancel the authenticated user's own registration.
     */
    public function cancel(
        Request $request,
        Training $training
    ): RedirectResponse {
        if (in_array($training->status, ['completed', 'cancelled'], true)) {
            return back()->with(
                'error',
                'Pendaftaran tidak dapat dibatalkan pada pelatihan ini.'
            );
        }

        $participant = TrainingParticipant::query()
            ->where('training_id', $training->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (
            $participant === null
            || in_array(
                $participant->registration_status,
                ['cancelled', 'rejected'],
                true
            )
        ) {
            return back()->with(
                'error',
                'Tidak ada pendaftaran aktif yang dapat dibatalkan.'
            );
        }

        $participant->update([
            'registration_status' => 'cancelled',
            'attendance_status' => 'not_marked',
        ]);

        return redirect()
            ->route('trainings.show', $training)
            ->with('success', 'Pendaftaran pelatihan berhasil dibatalkan.');
    }

    /**
     * Admin updates a participant's registration status.
     */
    public function updateRegistrationStatus(
        Request $request,
        Training $training,
        TrainingParticipant $participant
    ): RedirectResponse {
        abort_unless(
    (int) $participant->training_id === (int) $training->id,
    404
);

        $validated = $request->validate([
            'registration_status' => [
                'required',
                Rule::in([
                    'registered',
                    'approved',
                    'rejected',
                    'cancelled',
                ]),
            ],
        ]);

        $result = DB::transaction(function () use (
            $training,
            $participant,
            $validated
        ) {
            $lockedTraining = Training::query()
                ->whereKey($training->id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedParticipant = TrainingParticipant::query()
                ->whereKey($participant->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
    (int) $lockedParticipant->training_id === (int) $lockedTraining->id,
    404
);

            $oldStatus = $lockedParticipant->registration_status;
            $newStatus = $validated['registration_status'];

            $countedStatuses = ['registered', 'approved'];
            $wasCounted = in_array($oldStatus, $countedStatuses, true);
            $willBeCounted = in_array($newStatus, $countedStatuses, true);

            $activeRegistrations = TrainingParticipant::query()
                ->where('training_id', $lockedTraining->id)
                ->whereIn('registration_status', $countedStatuses)
                ->count();

            if (
                $willBeCounted
                && !$wasCounted
                && $lockedTraining->quota !== null
                && $activeRegistrations >= $lockedTraining->quota
            ) {
                return 'full';
            }

            $lockedParticipant->registration_status = $newStatus;

            if (in_array($newStatus, ['rejected', 'cancelled'], true)) {
                $lockedParticipant->attendance_status = 'not_marked';
            }

            $lockedParticipant->save();

            return 'success';
        });

        if ($result === 'full') {
            return back()->with(
                'error',
                'Kuota penuh. Pendaftaran tidak dapat diaktifkan.'
            );
        }

        return back()->with(
            'success',
            'Status pendaftaran peserta berhasil diperbarui.'
        );
    }

    /**
     * Admin records a participant's attendance.
     */
    public function updateAttendanceStatus(
        Request $request,
        Training $training,
        TrainingParticipant $participant
    ): RedirectResponse {
        abort_unless(
            $participant->training_id === $training->id,
            404
        );

        $validated = $request->validate([
            'attendance_status' => [
                'required',
                Rule::in(['not_marked', 'present', 'absent']),
            ],
        ]);

        $participant->refresh();

        if ($participant->registration_status !== 'approved') {
            return back()->with(
                'error',
                'Kehadiran hanya dapat dicatat untuk peserta yang sudah disetujui.'
            );
        }

        $participant->update([
            'attendance_status' => $validated['attendance_status'],
        ]);

        return back()->with(
            'success',
            'Status kehadiran peserta berhasil diperbarui.'
        );
    }
}