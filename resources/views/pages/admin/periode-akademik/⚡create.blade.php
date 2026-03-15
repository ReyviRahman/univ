<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\AcademicPeriod;

new #[Layout('layouts::admin')] class extends Component
{
    #[Validate('required|string', as: 'Tahun Akademik')]
    public $academic_year = '';

    #[Validate('required|in:ganjil,genap')]
    public $semester_type = 'ganjil';

    #[Validate('required|boolean')]
    public $is_active = false;

    public function save()
    {
        $this->validate();

        AcademicPeriod::create([
            'academic_year' => $this->academic_year,
            'semester_type' => $this->semester_type,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Tahun Akademik Berhasil Ditambahkan');
        $this->redirect(route('admin.academic-period.index'), navigate:true);
    }
};
?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.academic-period.index') }}" wire:navigate>Periode Akademik
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Tambah Periode Akademik</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class='text-center mb-5' size='xl' level='3'>Tambah Periode Akademik</flux:heading>

    <form wire:submit='save' class="space-y-6">
        <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
            <div>
                <label for="academic_year" class="block text-sm/6 font-medium text-secondary">Tahun Akademik</label>
                <div class="mt-2">
                    <input id="academic_year" type="text" wire:model="academic_year"
                        @class([ 'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6'
                        , 'outline-red-500 focus:outline-red-600'=> $errors->has('academic_year'),
                    'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => !
                    $errors->has('academic_year'),
                    ])
                    placeholder="2025 / 2026"
                    required
                    />
                    @error('academic_year')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label for="semester_type" class="block text-sm/6 font-medium text-secondary">Semester</label>
                <div class="mt-2 grid grid-cols-1">
                    <select id="semester_type" wire:model="semester_type"
                        @class([ 'outline-1 -outline-offset-1 col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base sm:text-sm/6'
                        , 'outline-red-500 focus:outline-red-600'=> $errors->has('semester_type'),
                        'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => !
                        $errors->has('semester_type'),

                        ])
                        >
                        <option value="ganjil">Ganjil</option>
                        <option value="genap">Genap</option>
                    </select>
                    <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true"
                        class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4">
                        <path
                            d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                </div>
                @error('semester')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="is_active" class="block text-sm/6 font-medium text-secondary">Status</label>
                <div class="mt-2 grid grid-cols-1">
                    <select id="is_active" wire:model="is_active"
                        @class([ 'outline-1 -outline-offset-1 col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base sm:text-sm/6'
                        , 'outline-red-500 focus:outline-red-600'=> $errors->has('is_active'),
                        'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => !
                        $errors->has('is_active'),

                        ])
                        >
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                    <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true"
                        class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4">
                        <path
                            d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                </div>
                @error('is_active')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-end sm:col-start-2">
                <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                    class="flex w-full justify-center items-center gap-2 rounded-md bg-accent px-3 py-1.5 text-sm/6 font-semibold text-secondary hover:bg-green-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-secondary transition-all">
                    <svg wire:loading class="animate-spin h-4 w-4 text-secondary" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>Simpan</span>
                </button>
            </div>
        </div>
    </form>
</div>