<?php

namespace App\Livewire\Admin\Prodi;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Validation\Rule;
use App\Models\Department;
use App\Models\Faculty;

new #[Layout('layouts::admin')] class extends Component
{
    public Department $department; // Model yang sedang diedit

    // Form Properties
    public $faculty_id = '';
    public $search = ''; // Input pencarian fakultas
    public $code = '';
    public $name = '';
    public $degree = '';

    // 1. Mount: Isi form dengan data dari database saat halaman dibuka
    public function mount(Department $department)
    {
        $this->department = $department;
        
        $this->faculty_id = $department->faculty_id;
        $this->search     = $department->faculty->name ?? ''; // Isi search dengan nama fakultas saat ini
        $this->code       = $department->code;
        $this->name       = $department->name;
        $this->degree     = $department->degree;
    }

    // 2. Computed: Logic pencarian fakultas (sama seperti create)
    #[Computed]
    public function faculties()
    {
        // 1. Cek dulu nama fakultas yang sedang dipilih sekarang
        $selectedFacultyName = null;
        if ($this->faculty_id) {
            $selectedFacultyName = Faculty::find($this->faculty_id)?->name;
        }

        // 2. LOGIC BARU: 
        // Jika kotak pencarian isinya SAMA PERSIS dengan nama yang dipilih,
        // berarti user belum ngetik pencarian baru. Tampilkan daftar default (tanpa filter).
        if ($this->search === $selectedFacultyName) {
            return Faculty::limit(10)->get();
        }

        // 3. Kalau teks beda (user mulai mengetik), baru lakukan filter
        return Faculty::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->limit(10) // Limit ditampilkan
            ->get();
    }

    // 3. Action saat memilih fakultas
    public function selectFaculty($id, $name)
    {
        $this->faculty_id = $id;
        $this->search = $name; 
    }

    // 4. Update Data
    public function update()
    {
        // Validasi Manual agar bisa pakai Rule::unique -> ignore
        $this->validate([
            'faculty_id' => 'required',
            // Cek unique code, TAPI abaikan ID prodi ini sendiri
            'code'       => ['required', Rule::unique('departments', 'code')->ignore($this->department->id)],
            'name'       => 'required',
            'degree'     => 'required',
        ]);

        $this->department->update([
            'faculty_id' => $this->faculty_id,
            'code'       => $this->code,
            'name'       => $this->name,
            'degree'     => $this->degree,
        ]);

        session()->flash('success', 'Program Studi Berhasil Diperbarui');
        $this->redirect(route('admin.prodi.index'), navigate: true);
    }
};
?>

<div class="max-w-4xl mx-auto">
    {{-- Breadcrumbs Manual --}}
    <nav class="flex mb-4 text-sm text-gray-500">
        <a href="{{ route('admin.prodi.index') }}" wire:navigate class="hover:text-gray-900">Program Studi</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">Edit Program Studi</span>
    </nav>

    {{-- Judul --}}
    <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">Edit Program Studi</h1>

    {{-- Form Container --}}
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <form wire:submit="update" class="space-y-6">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- === CUSTOM SEARCHABLE DROPDOWN (PURE HTML + ALPINE) === --}}
                <div class="sm:col-span-2 relative" x-data="{ open: false }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                    
                    <div class="relative">
                        {{-- Input Pencarian --}}
                        <input 
                            type="text" 
                            wire:model.live="search"
                            placeholder="Pilih atau cari fakultas..."
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                            autocomplete="off"
                            x-on:focus="open = true" 
                            x-on:click.outside="open = false"
                        >
                        
                        {{-- Icon Panah Bawah (Opsional) --}}
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    {{-- Error Message --}}
                    @error('faculty_id') 
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                    @enderror

                    {{-- Dropdown List --}}
                    <div 
                        x-show="open" 
                        style="display: none;"
                        class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
                    >
                        @forelse ($this->faculties as $faculty)
                            @php
                                // Cek apakah baris ini adalah yang sedang dipilih
                                $isSelected = $faculty->id == $this->faculty_id;
                            @endphp

                            <div 
                                wire:click="selectFaculty({{ $faculty->id }}, '{{ $faculty->name }}')"
                                @click="open = false" 
                                class="px-4 py-2 cursor-pointer text-sm flex justify-between items-center group transition
                                {{-- KONDISI WARNA: Jika dipilih pakai background biru muda, jika tidak putih --}}
                                {{ $isSelected ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}
                                "
                            >
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">{{ $faculty->name }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded
                                        {{ $isSelected ? 'bg-indigo-200 text-indigo-800' : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200' }}">
                                        {{ $faculty->code }}
                                    </span>
                                </div>

                                {{-- ICON CENTANG (Hanya muncul jika isSelected true) --}}
                                @if($isSelected)
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </div>
                        @empty
                            <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                Tidak ditemukan fakultas "{{ $this->search }}"
                            </div>
                        @endforelse
                    </div>
                </div>
                {{-- === END DROPDOWN === --}}

                {{-- Input Kode Prodi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Prodi</label>
                    <input 
                        type="text" 
                        wire:model="code"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        placeholder="Contoh: TI"
                    >
                    @error('code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Input Nama Prodi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Program Studi</label>
                    <input 
                        type="text" 
                        wire:model="name"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        placeholder="Contoh: Teknik Informatika"
                    >
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Select Gelar (Standard HTML Select) --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang / Gelar</label>
                    <select 
                        wire:model="degree"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white"
                    >
                        <option value="">Pilih Jenjang...</option>
                        <option value="D3">D3 - Diploma 3</option>
                        <option value="S1">S1 - Sarjana</option>
                        <option value="S2">S2 - Magister</option>
                        <option value="S3">S3 - Doktor</option>
                    </select>
                    @error('degree') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button 
                    type="submit" 
                    class="w-full sm:w-auto px-5 py-2.5 bg-accent hover:bg-lime-700 text-white font-medium rounded-md text-sm transition focus:ring-2 focus:ring-offset-2 focus:ring-lime-500"
                >
                    <span wire:loading.remove>Simpan Perubahan</span>
                    <span wire:loading>Menyimpan...</span>
                </button>

                <a 
                    href="{{ route('admin.prodi.index') }}" 
                    wire:navigate
                    class="w-full sm:w-auto px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-md text-sm text-center hover:bg-gray-50 transition"
                >
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
