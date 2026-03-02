<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate; 
use Livewire\Attributes\Computed;
use App\Models\Department;
use App\Models\Faculty;

new #[Layout('layouts::admin')] class extends Component
{
    // --- FORM DATA ---
    #[Validate('required', as: 'Fakultas')]
    public $faculty_id = ''; // Ini yang disimpan ke DB

    public $search = ''; // Ini untuk input pencarian user

    #[Validate('required|unique:departments,code', as: 'Kode Prodi')]
    public $code = '';

    #[Validate('required', as: 'Nama Prodi')]
    public $name = '';

    #[Validate('required', as: 'Gelar')]
    public $degree = '';

    // --- LOGIC FILTER ---
    #[Computed]
    public function faculties()
    {
        return Faculty::query()
            ->when($this->search, function ($query) {
                // Kalo ada search, cari berdasarkan nama/kode
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            // Kalo search kosong, dia bakal ambil semua (tapi dilimit)
            ->limit(5) 
            ->get();
    }

    // Fungsi saat user klik salah satu opsi
    public function selectFaculty($id, $name)
    {
        $this->faculty_id = $id;
        $this->search = $name; // Ubah teks input jadi nama yang dipilih
    }

    // Kalau user ngetik ulang, reset ID-nya (biar validasi jalan kalau dia ngasal)
    public function updatedSearch()
    {
        $this->faculty_id = ''; 
    }

    public function save() {
        $this->validate();

        Department::create([
            'faculty_id' => $this->faculty_id,
            'code'       => $this->code,
            'name'       => $this->name,
            'degree'       => $this->degree,
        ]);

        session()->flash('success', 'Program Studi Berhasil Ditambahkan');
        $this->redirect(route('admin.prodi.index'), navigate:true);
    }
};
?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.prodi.index') }}" wire:navigate>Program Studi
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Tambah Program Studi</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class='text-center' size='xl' level='3'>Tambah Program Studi</flux:heading>

    <form wire:submit='save'>
        <flux:card class="space-y-6 mt-4">

            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">

                {{-- === CUSTOM SEARCHABLE DROPDOWN (INSTANT OPEN) === --}}
                <div class="relative" x-data="{ open: false }">
                    {{-- 1. Input Pencarian --}}
                    <flux:input label="Fakultas" placeholder="Pilih atau cari fakultas..." wire:model.live="search"
                        icon-trailing="chevron-down" autocomplete="off" {{-- Event Handlers --}}
                        x-on:focus="open = true" x-on:click.outside="open = false" x-on:keydown.escape="open = false" />

                    {{-- Pesan Error --}}
                    @error('faculty_id') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    {{-- 2. Dropdown List --}}
                    {{-- HAPUS syarat length > 0, cukup cek 'open' saja --}}
                    <div x-show="open" x-transition
                        class="absolute z-50 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto"
                        style="display: none;">
                        @forelse ($this->faculties as $faculty)
                        <div wire:click="selectFaculty({{ $faculty->id }}, '{{ $faculty->name }}')"
                            @click="open = false"
                            class="px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 cursor-pointer text-sm text-zinc-900 dark:text-zinc-100 flex justify-between items-center">
                            <span>{{ $faculty->name }}</span>
                            <span class="text-xs text-zinc-500 bg-zinc-100 dark:bg-zinc-900 px-2 py-0.5 rounded">{{
                                $faculty->code }}</span>
                        </div>
                        @empty
                        <div class="px-4 py-3 text-sm text-zinc-500 text-center">
                            Tidak ditemukan.
                        </div>
                        @endforelse
                    </div>

                </div>
                {{-- === END DROPDOWN === --}}

                <div class="space-y-6">
                    <flux:input label="Kode Prodi" type="text" placeholder="Contoh: TI" wire:model='code' required />
                </div>
                <div class="space-y-6">
                    <flux:input label="Nama Program Studi" type="text" placeholder="Contoh: Teknik Informatika"
                        wire:model='name' required />
                </div>
                <div class="space-y-6">
                    <flux:select label="Gelar" wire:model="degree">
                        <flux:select.option value='D3' >D3</flux:select.option>
                        <flux:select.option value='S1'>S1</flux:select.option>
                        <flux:select.option value='S2'>S2</flux:select.option>
                        <flux:select.option value='S3'>S3</flux:select.option>
                    </flux:select>
                </div>
            </div>

            <div class="space-y-2">
                <flux:button variant="primary" class="w-full" type='submit'>Tambahkan</flux:button>
            </div>
        </flux:card>
    </form>
</div>