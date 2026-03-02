<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Student;

new class extends Component {
    #[Validate('required')] 
    #[Validate('unique:users,nim', message: 'Nim ini sudah terdaftar, gunakan yang lain.')]
    public $nim = '';

    #[Validate('required')]
    #[Validate('unique:users,email', message: 'Email ini sudah terdaftar, gunakan yang lain.')]
    public $email = '';

    #[Validate('required')]
    public $name = '';

    #[Validate('required')]
    public $password = '';

    #[Validate('required')]
    public $prodi = '';

    #[Validate('required')]
    #[Validate('unique:users,phone', message: 'No HP ini sudah terdaftar, gunakan yang lain.')]
    public $phone = '';

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
                    'advisor_id'          => $this->nidn,
                    'nim'          => $this->name,
                    'name'        => 'active',
                    'pob'        => 'active',
                    'dob'        => 'active',
                    'gender'        => 'active',
                    'phone'        => 'active',
                    'address'        => 'active',
                    'entry_year'        => 'active',
                    'status'        => 'active',
                ]);
            });

            session()->flash('success', 'Akun Berhasil Dibuat Silakan Login.');
            $this->redirect('/login');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }

    }
};
?>

<div class="min-h-screen">
    <div class="">
        <form wire:submit='save'>
            <flux:card class="space-y-6 w- mt-12 border-accent!">
                <flux:heading size="lg" class="text-center font-bold!">Daftar</flux:heading>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-6">
                        <div>
                            <flux:input label="NIM" type="text" placeholder="Masukkan NIM" wire:model='nim' required/>
                        </div>
                        <div>
                            <flux:input label="Nama Lengkap" type="text" placeholder="Masukkan Nama Lengkap" wire:model='name' required/>

                        </div>
                        <div>
                            <flux:input label="Program Studi" type="text" placeholder="Masukkan Program Studi" wire:model='prodi' required/>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <flux:input label="Email" type="email" placeholder="Your email address" wire:model='email' required/>

                        </div>
                        <div>
                            <flux:input label="Password" type="password" placeholder="Password" wire:model='password' required/>
                            
                        </div>
                        <div>
                            <flux:input label="No HP" type="text" placeholder="Masukkan No HP" wire:model='phone' required/>

                        </div>
                        
                    </div>
                    
    
                </div>
    
                <div class="grid grid-cols-2 gap-4 justify-end">
                    <div class="space-y-2 col-start-2">
                        <flux:button variant="primary" class="w-full block" type="submit" >Daftar</flux:button>
                        <flux:button href="/login" wire:navigate class="w-full">Login</flux:button>
                    </div>
                </div>
            </flux:card>
        </form>
    </div>
</div>
