<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;
use App\Models\User;
use App\Models\Student;
use App\Models\Department;
use App\Models\Lecturer;
use Illuminate\Support\Facades\DB;

new class extends Component {
  #[Validate('required')] 
  #[Validate('unique:users,username', message: 'Nim ini sudah terdaftar, gunakan yang lain.')]
  public $nim = '';

  #[Validate('required')]
  #[Validate('unique:users,email', message: 'Email ini sudah terdaftar, gunakan yang lain.')]
  public $email = '';

  #[Validate('required')]
  public $name = '';

  #[Validate('required')]
  public $password = '';

  public $search = '';
  public $searchAdvisor = '';

  #[Validate('required|exists:departments,id', as: 'Prodi')]
  public $department_id = '';

  #[Validate('required|exists:lecturers,id', as: 'Pembimbing')]
  public $advisor_id = '';

  #[Validate('required', as: 'Tempat Lahir')]
  public $pob = '';

  #[Validate('required', as: 'Tanggal Lahir')]
  #[Validate('date')]
  public $dob = '';
  
  #[Validate('required|in:L,P', as: 'Jenis Kelamin')]
  public $gender = 'L';

  #[Validate('required', as: 'Nomor HP')]
  #[Validate('unique:users,phone', message: 'No HP ini sudah terdaftar, gunakan yang lain.')]
  public $phone = '';

  #[Validate('required', as: 'Alamat')]
  public $address = '';

  #[Validate('required', as: 'Tahun Penerimaan')]
  public $entry_year = '';

  public $allDepartments;
  public function mount()
  {
    // Query dijalankan SEKALI saja saat inisialisasi
    // Kita simpan hasilnya ke property public agar bisa diakses di View
    $this->allDepartments = Department::select('id', 'name', 'code')
        ->orderBy('name')
        ->get();
        
    // Jika sedang mode edit (department_id sudah ada isinya),
    // Kita tidak perlu logic khusus di PHP, nanti Alpine yang handle text-nya.
  }

  #[Computed]
  public function departments() {
    $selectedDepartmentName = null;
    if ($this->department_id) {
      $selectedDepartmentName = Department::find($this->department_id)?->name;
    }

    if ($this->search === $selectedDepartmentName) {
      return Department::query()->limit(20)->get();
    }

    return Department::query()
      ->when($this->search, function ($query) {
          $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
      })
      ->limit(20)
      ->get();
  }

  public function selectDepartment($id, $name)
  {
    $this->department_id = $id;
    $this->search = $name; 
  }

  public function checkMatch()
  {
    // 1. Jika search kosong, kosongkan juga ID
    if (empty($this->search)) {
        $this->department_id = null;
        return;
    }

    // 2. Cek apakah inputan user SUDAH valid (sama persis dengan yang dipilih)
    // Jika ya, tidak perlu cari lagi.
    $currentName = Department::find($this->department_id)?->name;
    if ($this->search === $currentName) {
        return;
    }

    // 3. CARI DATA YANG PALING MIRIP (Match Pertama)
    $match = Department::query()
        ->where('name', 'like', '%' . $this->search . '%')
        ->orWhere('code', 'like', '%' . $this->search . '%')
        ->first(); // Ambil satu saja yang paling atas

    // 4. Jika ketemu, otomatis pilih
    if ($match) {
        $this->selectDepartment($match->id, $match->name);
    } else {
        // 5. Jika ngawur/tidak ketemu, reset ID-nya (biar tidak error saat save)
        $this->department_id = null;
    }
  }

  #[Computed]
  public function lecturers() {
    $selectedAdvisorName = null;
    if ($this->advisor_id) {
      $selectedAdvisorName = Lecturer::find($this->advisor_id)?->name;
    }

    if ($this->searchAdvisor === $selectedAdvisorName) {
      return Lecturer::query()->limit(20)->get();
    }

    return Lecturer::query()
      ->when($this->searchAdvisor, function($query) {
        $query->where('name', 'like', '%' . $this->searchAdvisor . '%');
      })
      ->limit(20)
      ->get();
  }

  public function selectLecturer($id, $name)
  {
    $this->advisor_id = $id;
    $this->searchAdvisor = $name; 
  }

  public function checkMatchAdvisor()
  {
    // 1. Jika searchAdvisor kosong, kosongkan juga ID
    if (empty($this->searchAdvisor)) {
        $this->advisor_id = null;
        return;
    }

    // 2. Cek apakah inputan user SUDAH valid (sama persis dengan yang dipilih)
    // Jika ya, tidak perlu cari lagi.
    $currentName = Lecturer::find($this->advisor_id)?->name;
    if ($this->searchAdvisor === $currentName) {
        return;
    }

    // 3. CARI DATA YANG PALING MIRIP (Match Pertama)
    $match = Lecturer::query()
        ->where('name', 'like', '%' . $this->searchAdvisor . '%')
        ->first(); // Ambil satu saja yang paling atas

    // 4. Jika ketemu, otomatis pilih
    if ($match) {
        $this->selectLecturer($match->id, $match->name);
    } else {
        // 5. Jika ngawur/tidak ketemu, reset ID-nya (biar tidak error saat save)
        $this->advisor_id = null;
    }
  }

  public function save() {
    $this->validate();
    try {
      DB::transaction(function () {
        $user = User::create([
          'username' => $this->nim,
          'email'    => $this->email,
          'password' => $this->password,
          'role'     => 'student',
        ]);

        Student::create([
          'user_id'       => $user->id,
          'department_id' => $this->department_id,
          'advisor_id'    => $this->advisor_id,
          'nim'          => $this->nim,
          'name'        => $this->name,
          'pob'        => $this->pob,
          'dob'        => $this->dob,
          'gender'     => $this->gender,
          'phone'        => $this->phone,
          'address'        => $this->address,
          'entry_year'        => $this->entry_year,
          'status'        => 'registered',
        ]);
      });

      session()->flash('success', 'Akun Berhasil Dibuat Silakan Login.');
      $this->redirect('/login', navigate:true);
    } catch (\Exception $e) {
      session()->flash('error', 'Gagal: ' . $e->getMessage());
    }
  }
};
?>

<div class="flex min-h-full flex-col justify-center px-6  lg:px-8">
  <div class="">
    <form wire:submit='save' class="space-y-6">
      <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
        <div>
          <label for="name" class="block text-sm/6 font-medium text-secondary">Nama Lengkap</label>
          <div class="mt-2">
            <input 
              id="name" 
              type="text" 
              wire:model="name" 
              @class([
                  'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                  'outline-red-500 focus:outline-red-600' => $errors->has('name'),
                  'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('name'),
              ])
              placeholder="Masukkan Nama Lengkap"
            />
            @error('name')
              <span class="text-sm text-red-600" >{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div>
          <label for="nim" class="block text-sm/6 font-medium text-secondary">NIM</label>
          <div class="mt-2">
            <input 
              id="nim" 
              type="text" 
              wire:model="nim" 
              @class([
                  'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                  'outline-red-500 focus:outline-red-600' => $errors->has('nim'),
                  'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('nim'),
              ])
              placeholder="Masukkan NIM"
            />
            @error('nim')
              <span class="text-sm text-red-600" >{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div>
          <label for="email" class="block text-sm/6 font-medium text-secondary">Email</label>
          <div class="mt-2">
            <input 
              id="email" 
              type="text" 
              wire:model="email" 
              @class([
                  'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                  'outline-red-500 focus:outline-red-600' => $errors->has('email'),
                  'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('email'),
              ])
              placeholder="Masukkan Email"
          />
            @error('email')
              <span class="text-sm text-red-600" >{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div>
          <label for="password" class="block text-sm/6 font-medium text-secondary">Password</label>
          
          {{-- 1. Tambahkan x-data di sini untuk state show/hide --}}
          <div class="mt-2" x-data="{ show: false }"> 
              
              {{-- 2. Tambahkan class relative agar icon bisa ditaruh di dalam input --}}
              <div class="relative">
                  <input 
                      id="password" 
                      {{-- 3. Ubah type jadi dinamis --}}
                      :type="show ? 'text' : 'password'" 
                      wire:model="password" 
                      @class([
                          'block w-full rounded-md bg-white/5 py-1.5 pl-3 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                          'pr-10', // 4. Tambah padding kanan (pr-10) agar teks tidak tertutup icon
                          'outline-red-500 focus:outline-red-600' => $errors->has('password'),
                          'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('password'),
                      ])
                      placeholder="Masukkan Password"
                  />

                  {{-- 5. Tombol Icon Mata --}}
                  <button 
                      type="button" 
                      @click="show = !show" 
                      class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none"
                  >
                      {{-- Icon Mata Terbuka (Muncul saat password tersembunyi/show=false) --}}
                      <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                      </svg>

                      {{-- Icon Mata Dicoret (Muncul saat password terlihat/show=true) --}}
                      <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5" style="display: none;">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                      </svg>
                  </button>
              </div>

              @error('password')
                  <span class="text-sm text-red-600">{{ $message }}</span>
              @enderror
          </div>
      </div>
        <div>
          <div 
            class="relative"
            x-data="{
                open: false,
                search: '',
                selectedId: @entangle('department_id'),
                items: @js($allDepartments),
                
                // 1. Logic Filter Tampilan (Limit 20)
                get filteredItems() {
                    const currentItem = this.items.find(i => i.id == this.selectedId);
                    
                    // Tampilkan semua jika search kosong atau search == item terpilih
                    if (this.search === '' || (currentItem && this.search === currentItem.name)) {
                        return this.items.slice(0, 20);
                    }
                    
                    return this.matchItems(this.search).slice(0, 20);
                },

                // 2. Helper: Fungsi Pencarian Murni (Tanpa Limit)
                // Kita pisah fungsi ini agar bisa dipakai oleh filteredItems DAN autoSelect
                matchItems(term) {
                    return this.items.filter(item => {
                        return item.name.toLowerCase().includes(term.toLowerCase()) 
                            || (item.code && item.code.toLowerCase().includes(term.toLowerCase()));
                    });
                },

                // 3. Fungsi Utama: Pilih Item
                selectItem(item) {
                    this.selectedId = item.id;
                    this.search = item.name;
                    this.open = false;
                },

                // 4. LOGIKA BARU: Auto Select Match Terdekat
                autoSelect() {
                    // Jika kosong, reset
                    if (this.search === '') {
                        this.selectedId = null;
                        return;
                    }

                    // Cek apakah teks sekarang sudah valid (sama persis dengan yang dipilih)
                    const currentItem = this.items.find(i => i.id == this.selectedId);
                    if (currentItem && this.search === currentItem.name) {
                        return; // Sudah benar, jangan diganggu
                    }

                    // Cari match terdekat (ambil hasil pertama)
                    const matches = this.matchItems(this.search);

                    if (matches.length > 0) {
                        // KETEMU! Pilih yang paling atas otomatis
                        this.selectItem(matches[0]);
                    } else {
                        // TIDAK KETEMU! Reset pilihan & teks
                        this.selectedId = null;
                        this.search = ''; // Atau biarkan textnya biar user tau dia salah ketik
                    }
                },

                init() {
                    if (this.selectedId) {
                        const found = this.items.find(i => i.id == this.selectedId);
                        if (found) this.search = found.name;
                    }
                    this.$watch('selectedId', value => {
                        if (!value) this.search = '';
                    });
                }
            }"
        >
          <label for="prodi" class="block text-sm/6 font-medium text-secondary">Program Studi</label>
          
          <div class="relative mt-2">
            {{-- INPUT SEARCH --}}
            <input 
              type="text" 
              x-model="search"
              placeholder="Pilih atau cari fakultas..."
              @class([
                'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                'outline-red-500 focus:outline-red-600' => $errors->has('department_id'),
                'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('department_id'),
              ])
              autocomplete="off"
              @focus="open = true" 
              @input="open = true" 
              @click.outside="open = false; autoSelect()"
              @blur="autoSelect()"
            >
            {{-- ICON PANAH --}}
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            {{-- TOMBOL CLEAR (X) --}}
            <button 
                x-show="search.length > 0" 
                @click="search = ''; selectedId = null; open = true;"
                type="button"
                class="absolute inset-y-0 right-8 flex items-center pr-2 text-gray-400 hover:text-red-500 cursor-pointer focus:outline-none"
            >
              &times;
            </button>
          </div>

          {{-- PESAN ERROR --}}
          @error('department_id') 
              <span class="text-sm text-red-600">{{ $message }}</span> 
          @enderror

        {{-- DROPDOWN LIST --}}
          <div 
              x-show="open" 
              x-transition
              style="display: none;"
              class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
          >
            <template x-for="item in filteredItems" :key="item.id">
                <div 
                    @click="selectItem(item)"
                    class="px-4 py-2 cursor-pointer text-sm flex justify-between items-center group transition border-b border-gray-100 last:border-0"
                    :class="{ 
                        'bg-green-50 text-green-700': selectedId == item.id, 
                        'text-gray-700 hover:bg-gray-50': selectedId != item.id 
                    }"
                >
                    <div class="flex items-center gap-2">
                        <span class="font-medium" x-text="item.name"></span>
                        <span 
                            class="text-xs px-2 py-0.5 rounded"
                            :class="{ 
                                'bg-green-200 text-green-800': selectedId == item.id, 
                                'bg-gray-100 text-gray-500 group-hover:bg-gray-200': selectedId != item.id 
                            }"
                            x-text="item.code"
                        ></span>
                    </div>

                    {{-- ICON CENTANG --}}
                    <svg x-show="selectedId == item.id" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </template>

            {{-- JIKA KOSONG --}}
            <div x-show="filteredItems.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                Tidak ditemukan fakultas "<span x-text="search"></span>"
            </div>
          </div>
        </div>
      </div>

        <div>
          <div class="relative" x-data="{ open: false }">
            <label for="advisor" class="block text-sm/6 font-medium text-secondary">Dosen Pembimbing</label>
            
            <div class="relative mt-2">
                {{-- Input Pencarian --}}
                <input 
                    type="text" 
                    wire:model.live="searchAdvisor"
                    wire:blur="checkMatchAdvisor"
                    placeholder="Pilih atau cari Dosen..."
                    @class([
                      'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                      'outline-red-500 focus:outline-red-600' => $errors->has('advisor_id'),
                      'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('advisor_id'),
                    ])
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
            @error('advisor_id') 
                <span class="text-sm text-red-600" >{{ $message }}</span> 
            @enderror

            {{-- Dropdown List --}}
            <div 
                x-show="open" 
                style="display: none;"
                class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
            >
                @forelse ($this->lecturers as $lecturer)
                    @php
                        // Cek apakah baris ini adalah yang sedang dipilih
                        $isSelected = $lecturer->id == $this->advisor_id;
                    @endphp

                    <div 
                        wire:click="selectLecturer({{ $lecturer->id }}, '{{ $lecturer->name }}')"
                        @click="open = false" 
                        class="px-4 py-2 cursor-pointer text-sm flex justify-between items-center group transition
                        {{-- KONDISI WARNA: Jika dipilih pakai background biru muda, jika tidak putih --}}
                        {{ $isSelected ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}
                        "
                    >
                        <div class="flex items-center gap-2">
                            <span class="font-medium">{{ $lecturer->name }}</span>
                        </div>

                        {{-- ICON CENTANG (Hanya muncul jika isSelected true) --}}
                        @if($isSelected)
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        @endif
                    </div>
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                        Tidak ditemukan Dosen "{{ $this->searchAdvisor }}"
                    </div>
                @endforelse
            </div>
          </div>
        </div>
        <div>
          <label for="pob" class="block text-sm/6 font-medium text-secondary">Tempat Lahir</label>
          <div class="mt-2">
            <input 
              id="pob" 
              type="text" 
              wire:model="pob" 
              @class([
                  'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                  'outline-red-500 focus:outline-red-600' => $errors->has('pob'),
                  'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('pob'),
              ])
              placeholder="Masukkan Tempat Lahir"

            />
            @error('pob')
              <span class="text-sm text-red-600" >{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div>
          <label for="dob" class="block text-sm/6 font-medium text-secondary">Tanggal Lahir</label>
          <div class="mt-2">
            <input 
              id="dob" 
              type="date" 
              wire:model="dob" 
              @class([
                  'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                  'outline-red-500 focus:outline-red-600' => $errors->has('dob'),
                  'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('dob'),
              ])
            />
            @error('dob')
              <span class="text-sm text-red-600" >{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div>
          <label for="gender" class="block text-sm/6 font-medium text-secondary">Jenis Kelamin</label>
          <div class="mt-2 grid grid-cols-1">
            <select 
              id="gender"
              wire:model="gender"
              @class([
                'outline-1 -outline-offset-1 col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base sm:text-sm/6',
                'outline-red-500 focus:outline-red-600' => $errors->has('gender'),
                'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('gender'),

              ])
            >
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
            <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4">
              <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
            </svg>
          </div>
          @error('gender')
            <span class="text-sm text-red-600" >{{ $message }}</span>
          @enderror
        </div>
        <div>
          <label for="phone" class="block text-sm/6 font-medium text-secondary">Nomor HP</label>
          <div class="mt-2">
            <input 
              id="phone" 
              type="number" 
              wire:model="phone" 
              @class([
                  'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                  'outline-red-500 focus:outline-red-600' => $errors->has('phone'),
                  'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('phone'),
              ])
              placeholder="Masukkan Nomor HP"
            />
            @error('phone')
              <span class="text-sm text-red-600" >{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div>
          <label for="address" class="block text-sm/6 font-medium text-secondary">Alamat</label>
          <div class="mt-2">
            <input 
              id="address" 
              type="text" 
              wire:model="address" 
              @class([
                  'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                  'outline-red-500 focus:outline-red-600' => $errors->has('address'),
                  'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('address'),
              ])
              placeholder="Masukkan Alamat"
            />
            @error('address')
              <span class="text-sm text-red-600" >{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div>
          <label for="entry_year" class="block text-sm/6 font-medium text-secondary">Tahun Penerimaan</label>
          <div class="mt-2">
            <input 
              id="entry_year" 
              type="number" 
              wire:model="entry_year" 
              @class([
                  'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
                  'outline-red-500 focus:outline-red-600' => $errors->has('entry_year'),
                  'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('entry_year'),
              ])
              placeholder="Masukkan Tahun Penerimaan"
            />
            @error('entry_year')
              <span class="text-sm text-red-600" >{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="sm:col-start-2">
          <button 
              type="submit" 
              wire:loading.attr="disabled"
              wire:loading.class="opacity-50 cursor-not-allowed"
              class="flex w-full justify-center items-center gap-2 rounded-md bg-accent px-3 py-1.5 text-sm/6 font-semibold text-secondary hover:bg-green-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-secondary transition-all"
          >
              <svg 
                  wire:loading 
                  class="animate-spin h-4 w-4 text-secondary" 
                  xmlns="http://www.w3.org/2000/svg" 
                  fill="none" 
                  viewBox="0 0 24 24"
              >
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>Daftar</span>
          </button>
        </div>
      </div>
    </form>

    <p class="mt-10 text-center text-sm/6 text-gray-400">
      Not a member?
      <a href="#" class="font-semibold text-indigo-400 hover:text-indigo-300">Start a 14 day free trial</a>
    </p>
  </div>
</div>
