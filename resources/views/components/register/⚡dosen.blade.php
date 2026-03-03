<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Department;

new class extends Component
{
    #[Validate('required')] 
    #[Validate('unique:users,username', message: 'NIDN ini sudah terdaftar, gunakan yang lain.')]
    public $nidn = '';

    #[Validate('required')] 
    public $email = '';

    #[Validate('required')] 
    public $password = '';

    #[Validate('required|exists:departments,id', as: 'Prodi')]
    public $department_id = '';

    #[Validate('required')] 
    public $name = '';

    #[Validate('required', as: 'No HP')] 
    public $phone = '';
    
    public function save() {
			$this->validate();
			try {
				DB::transaction(function () {
					$user = User::create([
						'username' => $this->nidn,
						'email' => $this->email,
						'password' => $this->password,
						'role' => 'lecturer',
					]);

					Lecturer::create([
						'user_id' => $user->id,
						'department_id' => $this->department_id,
						'nidn' => $this->nidn,
						'name' => $this->name,
						'phone' => $this->phone,
						'status' => 'active',
					]);
				});
				session()->flash('success', 'Akun Berhasil Dibuat Silakan Login.');
				$this->redirect('/login', navigate:true);
			} catch (\Exception $e) {
				session()->flash('error', $e->getMessage());
			}
    }

	public $allDepartments;
  public function mount()
  {
    $this->allDepartments = Department::select('id', 'name', 'code')
        ->orderBy('name')
        ->get();
  }

};
?>

<div class="flex min-h-full flex-col justify-center px-6 lg:px-8">
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
						required
					/>
					@error('name')
						<span class="text-sm text-red-600" >{{ $message }}</span>
					@enderror
				</div>
			</div>
			<div>
				<label for="nidn" class="block text-sm/6 font-medium text-secondary">NIDN</label>
				<div class="mt-2">
					<input 
						id="nidn" 
						type="text" 
						wire:model="nidn" 
						@class([
								'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
								'outline-red-500 focus:outline-red-600' => $errors->has('nidn'),
								'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('nidn'),
						])
						placeholder="Masukkan NIDN"
						required
					/>
					@error('nidn')
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
				<div class="mt-2" x-data="{ show: false }"> 
					<div class="relative">
						<input 
							id="password" 
							:type="show ? 'text' : 'password'" 
							wire:model="password" 
							@class([
								'block w-full rounded-md bg-white/5 py-1.5 pl-3 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
								'pr-10', 
								'outline-red-500 focus:outline-red-600' => $errors->has('password'),
								'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('password'),
							])
							placeholder="Masukkan Password"
						/>
							<button 
								type="button" 
								@click="show = !show" 
								class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none"
							>
								<svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
									<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
									<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
								</svg>

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
					class='relative'
					x-data="{
						open: false,
						search: '',
						selectedId: @entangle('department_id'),
            items: @js($allDepartments),
						get filteredItems() {
							const currentItem = this.items.find(i => i.id == this.selectedId);
							if (this.search === '' || (currentItem && this.search === currentItem.name)) {
									return this.items.slice(0, 20);
							}
							return this.matchItems(this.search).slice(0, 20);
						},
						matchItems(term) {
							return this.items.filter(item => {
								return item.name.toLowerCase().includes(term.toLowerCase()) 
									|| (item.code && item.code.toLowerCase().includes(term.toLowerCase()));
							});
						},
						selectItem(item) {
							this.selectedId = item.id;
							this.search = item.name;
							this.open = false;
						},
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

					@error('department_id') 
            <span class="text-sm text-red-600">{{ $message }}</span> 
          @enderror

					{{-- Dropdownlist --}}
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
						required
					/>
					@error('phone')
						<span class="text-sm text-red-600" >{{ $message }}</span>
					@enderror
				</div>
			</div>

			<div class="flex items-end">
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
</div>