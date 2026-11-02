<div class="space-y-6">

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div>
            <label for="code" class="mb-2 block text-sm font-semibold text-slate-700">
                Competency Code <span class="text-red-500">*</span>
            </label>

            <input
                id="code"
                name="code"
                type="text"
                required
                maxlength="50"
                value="{{ old('code', $competency->code ?? '') }}"
                placeholder="INST-PLC-001"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >

            @error('code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                Competency Name <span class="text-red-500">*</span>
            </label>

            <input
                id="name"
                name="name"
                type="text"
                required
                maxlength="150"
                value="{{ old('name', $competency->name ?? '') }}"
                placeholder="PLC Troubleshooting"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >

            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">
                Category <span class="text-red-500">*</span>
            </label>

            <input
                id="category"
                name="category"
                type="text"
                required
                maxlength="100"
                value="{{ old('category', $competency->category ?? '') }}"
                placeholder="Instrumentation & Control"
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >

            @error('category')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label for="description"
                   class="mb-2 block text-sm font-semibold text-slate-700">
                Description
            </label>

            <textarea
                id="description"
                name="description"
                rows="5"
                maxlength="10000"
                placeholder="Describe the technical competency and expected capabilities..."
                class="w-full rounded-xl border border-slate-300 px-4 py-3
                       text-sm focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-100"
            >{{ old('description', $competency->description ?? '') }}</textarea>

            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label class="flex items-center gap-3 rounded-xl border
                          border-slate-200 p-4">

                <input type="hidden" name="is_active" value="0">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked((bool) old('is_active', $competency->is_active ?? true))
                    class="h-4 w-4 rounded border-slate-300 text-blue-600
                           focus:ring-blue-500"
                >

                <span>
                    <span class="block text-sm font-semibold text-slate-800">
                        Active Competency
                    </span>
                    <span class="mt-1 block text-xs text-slate-500">
                        Kompetensi aktif dapat digunakan untuk penilaian karyawan.
                    </span>
                </span>
            </label>

            @error('is_active')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-200
                pt-5 sm:flex-row sm:justify-end">

        <a href="{{ isset($competency)
                    ? route('competencies.show', $competency)
                    : route('competencies.index') }}"
           class="inline-flex justify-center rounded-xl border
                  border-slate-300 px-5 py-3 text-sm font-semibold
                  text-slate-600 hover:bg-slate-50">
            Cancel
        </a>

        <button
            type="submit"
            class="inline-flex justify-center rounded-xl bg-blue-600
                   px-5 py-3 text-sm font-semibold text-white
                   shadow-sm transition hover:bg-blue-700"
        >
            {{ isset($competency) ? 'Save Changes' : 'Create Competency' }}
        </button>

    </div>

</div>