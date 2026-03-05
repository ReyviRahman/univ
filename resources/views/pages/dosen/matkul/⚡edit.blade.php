<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\Subject;
use Illuminate\Validation\Rule;

new #[Layout('layouts::dosen')] class extends Component
{
    public Subject $subject;

    public $code = '';

    #[Validate('required')]
    public $name = '';

    #[Validate('required|integer')]
    public $sks = '';

    #[Validate('required|integer')]
    public $semester = '';

    #[Validate('required|boolean', as: "Status")]
    public $is_active = 1;

    public function rules()
    {
        return [
            'code' => [
                'required',
                Rule::unique('subjects', 'code')->ignore($this->subject->id),
            ]
        ];
    }

    public function mount(Subject $subject)
    {
        $this->subject = $subject;

        $this->code = $subject->code;
        $this->name = $subject->name;
        $this->sks = $subject->sks;
        $this->semester = $subject->semester;
        $this->is_active = $subject->is_active;
    }

    public function save()
    {
        $this->validate();

        $this->subject->update([
            'code' => $this->code,
            'name' => $this->name,
            'sks' => $this->sks,
            'semester' => $this->semester,
            'is_active' => $this->is_active,
        ]); 

        session()->flash('success', 'Mata Kuliah Berhasil Diubah');
        $this->redirect(route('dosen.matkul.index'), navigate:true);
    }
};
?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dosen.matkul.index') }}" wire:navigate >Mata Kuliah</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Mata Kuliah</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class='text-center mb-5' size='xl' level='3' >Edit Mata Kuliah</flux:heading>

    <form wire:submit='save' class="space-y-6">
        <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
          <div>
						<label for="code" class="block text-sm/6 font-medium text-secondary">Kode Mata Kuliah</label>
							<div class="mt-2">
								<input 
									id="code" 
									type="text" 
									wire:model="code" 
									@class([
											'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
											'outline-red-500 focus:outline-red-600' => $errors->has('code'),
											'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('code'),
									])
									placeholder="Masukkan Kode Mata Kuliah"
									required
								/>
								@error('code')
									<span class="text-sm text-red-600" >{{ $message }}</span>
								@enderror
							</div>
            </div>
            <div>
							<label for="name" class="block text-sm/6 font-medium text-secondary">Nama Mata Kuliah</label>
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
									placeholder="Masukkan Nama Mata Kuliah"
									required
								/>
								@error('name')
									<span class="text-sm text-red-600" >{{ $message }}</span>
								@enderror
							</div>
            </div>
            <div>
				<label for="sks" class="block text-sm/6 font-medium text-secondary">Jumlah SKS</label>
                <div class="mt-2">
					<input 
						id="sks" 
						type="number" 
						wire:model="sks" 
						@class([
								'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
								'outline-red-500 focus:outline-red-600' => $errors->has('sks'),
								'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('sks'),
						])
						placeholder="Masukkan Jumlah SKS"
						required
					/>
					@error('sks')
						<span class="text-sm text-red-600" >{{ $message }}</span>
					@enderror
				</div>
            </div>
            <div>
							<label for="semester" class="block text-sm/6 font-medium text-secondary">Semester</label>
							<div class="mt-2">
								<input 
									id="semester" 
									type="number" 
									wire:model="semester" 
									@class([
											'block w-full rounded-md bg-white/5 px-3 py-1.5 text-base outline-1 -outline-offset-1 placeholder:text-gray-500 sm:text-sm/6',
											'outline-red-500 focus:outline-red-600' => $errors->has('semester'),
											'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('semester'),
									])
									placeholder="Masukkan Semester"
									required
								/>
								@error('semester')
									<span class="text-sm text-red-600" >{{ $message }}</span>
								@enderror
							</div>
            </div>

						<div>
							<label for="is_active" class="block text-sm/6 font-medium text-secondary">Status</label>
							<div class="mt-2 grid grid-cols-1">
								<select 
									id="is_active"
									wire:model="is_active"
									@class([
										'outline-1 -outline-offset-1 col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base sm:text-sm/6',
										'outline-red-500 focus:outline-red-600' => $errors->has('is_active'),
										'outline-accent focus:outline-2 focus:-outline-offset-2 focus:outline-secondary' => ! $errors->has('is_active'),

									])
								>
									<option value="1">Aktif</option>
									<option value="0">Tidak Aktif</option>
								</select>
								<svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4">
									<path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
								</svg>
							</div>
							@error('is_active')
								<span class="text-sm text-red-600" >{{ $message }}</span>
							@enderror
						</div>


            <div class="flex items-end sm:col-start-2">
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
                    <span>Simpan</span>
                </button>
            </div>
        </div>
    </form>
</div>