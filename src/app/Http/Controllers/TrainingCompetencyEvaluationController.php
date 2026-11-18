<?php

namespace App\Http\Controllers;

use App\Models\Competency;
use App\Models\EmployeeCompetency;
use App\Models\EmployeeCompetencyAssessment;
use App\Models\Training;
use App\Models\TrainingCompetencyEvaluation;
use App\Models\TrainingParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TrainingCompetencyEvaluationController extends Controller
{
    /**
     * Menampilkan form evaluasi kompetensi setelah training.
     */
    public function create(
        Training $training,
        TrainingParticipant $participant
    ): View|RedirectResponse {
        abort_unless(
            (int) $participant->training_id === (int) $training->id,
            404
        );

        if (!$this->isEligible($training, $participant)) {
            return redirect()
                ->route('trainings.show', $training)
                ->with(
                    'error',
                    'Peserta belum memenuhi syarat untuk evaluasi kompetensi.'
                );
        }

        // Kompetensi yang terhubung dengan training.
        $competencies = $training->competencies()
            ->orderBy('competencies.name')
            ->get();

        $competencyIds = $competencies->pluck('id');

        // Kompetensi yang dimiliki oleh peserta.
        $employeeCompetencies = EmployeeCompetency::query()
            ->where('user_id', $participant->user_id)
            ->whereIn('competency_id', $competencyIds)
            ->get()
            ->keyBy('competency_id');

        // Kompetensi yang sudah dievaluasi pada training ini.
        $evaluatedIds = TrainingCompetencyEvaluation::query()
            ->where('training_participant_id', $participant->id)
            ->pluck('competency_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        /*
         * Ambil riwayat kompetensi terakhir sebelum atau pada
         * tanggal mulai training.
         *
         * Riwayat setelah training dimulai tidak boleh menjadi baseline.
         */
        $assessmentHistories = EmployeeCompetencyAssessment::query()
            ->where('user_id', $participant->user_id)
            ->whereIn('competency_id', $competencyIds)
            ->whereDate('assessed_at', '<=', $training->start_date)
            ->orderByDesc('assessed_at')
            ->orderByDesc('id')
            ->get()
            ->unique('competency_id')
            ->keyBy('competency_id');

        // Hanya tampilkan kompetensi yang dimiliki dan belum dievaluasi.
        $availableCompetencies = $competencies
            ->filter(function ($competency) use (
                $employeeCompetencies,
                $evaluatedIds
            ) {
                return $employeeCompetencies->has($competency->id)
                    && !in_array(
                        (int) $competency->id,
                        $evaluatedIds,
                        true
                    );
            })
            ->values();

        /*
         * Tambahkan informasi baseline dan target untuk ditampilkan
         * pada halaman form evaluasi.
         */
        foreach ($availableCompetencies as $competency) {
            $assignment = $employeeCompetencies->get($competency->id);

            $history = $assessmentHistories->get($competency->id);

            $competency->setAttribute(
                'baseline_level',
                $history?->current_level
            );

            $competency->setAttribute(
                'evaluation_target_level',
                $history?->target_level ?? $assignment->target_level
            );
        }

        return view('training-evaluations.create', [
            'training' => $training,
            'participant' => $participant,
            'competencies' => $availableCompetencies,
        ]);
    }

    /**
     * Menyimpan hasil evaluasi kompetensi setelah training.
     */
    public function store(
        Request $request,
        Training $training,
        TrainingParticipant $participant
    ): RedirectResponse {
        abort_unless(
            (int) $participant->training_id === (int) $training->id,
            404
        );

        $request->validate([
            'competency_id' => [
                'required',
                'integer',
                'exists:competencies,id',
            ],
            'post_training_level' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'assessed_at' => [
                'required',
                'date',
                'after_or_equal:' . $training->end_date,
                'before_or_equal:' . now()->toDateString(),
            ],
            'evidence' => [
                'nullable',
                'string',
                'max:10000',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:10000',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $training,
            $participant
        ) {
            // Lock data untuk mencegah proses evaluasi bersamaan.
            $lockedTraining = Training::query()
                ->lockForUpdate()
                ->findOrFail($training->id);

            $lockedParticipant = TrainingParticipant::query()
                ->lockForUpdate()
                ->findOrFail($participant->id);

            if (
                (int) $lockedParticipant->training_id
                !== (int) $lockedTraining->id
            ) {
                throw ValidationException::withMessages([
                    'competency_id' => 'Peserta tidak terdaftar pada training ini.',
                ]);
            }

            if (!$this->isEligible($lockedTraining, $lockedParticipant)) {
                throw ValidationException::withMessages([
                    'competency_id' => 'Peserta belum memenuhi syarat evaluasi.',
                ]);
            }

            $competencyId = (int) $request->competency_id;

            // Pastikan kompetensi terhubung dengan training.
            $isMapped = $lockedTraining->competencies()
                ->where('competencies.id', $competencyId)
                ->exists();

            if (!$isMapped) {
                throw ValidationException::withMessages([
                    'competency_id' => 'Kompetensi tidak terdaftar pada training ini.',
                ]);
            }

            // Pastikan peserta memiliki kompetensi tersebut.
            $employeeCompetency = EmployeeCompetency::query()
                ->where('user_id', $lockedParticipant->user_id)
                ->where('competency_id', $competencyId)
                ->lockForUpdate()
                ->first();

            if (!$employeeCompetency) {
                throw ValidationException::withMessages([
                    'competency_id' => 'Peserta belum memiliki penugasan kompetensi ini.',
                ]);
            }

            // Cegah evaluasi ganda.
            $alreadyEvaluated = TrainingCompetencyEvaluation::query()
                ->where(
                    'training_participant_id',
                    $lockedParticipant->id
                )
                ->where('competency_id', $competencyId)
                ->exists();

            if ($alreadyEvaluated) {
                throw ValidationException::withMessages([
                    'competency_id' => 'Kompetensi ini sudah dievaluasi untuk training tersebut.',
                ]);
            }

            /*
             * Ambil baseline terakhir sebelum atau pada tanggal
             * mulai training.
             */
            $baseline = EmployeeCompetencyAssessment::query()
                ->where('user_id', $lockedParticipant->user_id)
                ->where('competency_id', $competencyId)
                ->whereDate('assessed_at', '<=', $lockedTraining->start_date)
                ->orderByDesc('assessed_at')
                ->orderByDesc('id')
                ->first();

            $competency = Competency::findOrFail($competencyId);

            $participantUser = $lockedParticipant->user;

            $assessor = Auth::user();

            $preTrainingLevel = $baseline?->current_level;

            $targetLevel = $baseline?->target_level
                ?? $employeeCompetency->target_level;

            $postTrainingLevel = (int) $request->post_training_level;

            /*
             * Simpan snapshot evaluasi training.
             */
            TrainingCompetencyEvaluation::create([
                'training_participant_id' => $lockedParticipant->id,
                'employee_competency_id' => $employeeCompetency->id,
                'user_id' => $lockedParticipant->user_id,
                'competency_id' => $competency->id,
                'assessed_by' => Auth::id(),

                'training_title' => $lockedTraining->title
                    ?? $lockedTraining->training_name
                    ?? 'Training #' . $lockedTraining->id,

                'employee_name' => $participantUser->name
                    ?? 'Employee #' . $participantUser->id,

                'competency_code' => $competency->code,
                'competency_name' => $competency->name,
                'competency_category' => $competency->category,

                'pre_training_level' => $preTrainingLevel,
                'post_training_level' => $postTrainingLevel,
                'target_level' => $targetLevel,

                'assessed_at' => $request->assessed_at,
                'evidence' => $request->evidence,
                'notes' => $request->notes,
            ]);

            /*
             * Perbarui kompetensi karyawan.
             * Target level tidak diubah.
             */
            $employeeCompetency->update([
                'current_level' => $postTrainingLevel,
                'assessed_by' => Auth::id(),
                'assessed_at' => $request->assessed_at,
                'evidence' => $request->evidence,
                'notes' => $request->notes,
            ]);

            /*
             * Simpan juga ke riwayat assessment kompetensi.
             */

           /*
            |--------------------------------------------------------------------------
            | Simpan Riwayat Assessment Kompetensi
            |--------------------------------------------------------------------------
            */

            EmployeeCompetencyAssessment::create([

                // Relasi employee competency
                'employee_competency_id' => $employeeCompetency->id,

                // Identitas karyawan
                'user_id' => $lockedParticipant->user_id,
                'employee_name' => $participantUser->name
                    ?? 'Employee #' . $lockedParticipant->user_id,

                // Identitas kompetensi
                'competency_id' => $competency->id,
                'competency_code' => $competency->code,
                'competency_name' => $competency->name,
                'competency_category' => $competency->category,

                // Hasil assessment
                'current_level' => $postTrainingLevel,
                'target_level' => $targetLevel,

                // Informasi assessor
                'assessed_at' => $request->assessed_at,
                'assessed_by' => Auth::id(),
                'assessor_name' => $assessor?->name ?? 'Unknown Assessor',

                // Bukti dan catatan
                'evidence' => $request->evidence,
                'notes' => $request->notes,
            ]);
        });

        return redirect()
            ->route('trainings.show', $training)
            ->with(
                'success',
                'Evaluasi kompetensi setelah training berhasil disimpan.'
            );
    }

    /**
     * Memeriksa kelayakan peserta mengikuti evaluasi.
     */
    private function isEligible(
        Training $training,
        TrainingParticipant $participant
    ): bool {
        return $training->status === 'completed'
            && $participant->registration_status === 'approved'
            && $participant->attendance_status === 'present';
    }
}