<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;

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

        User::create(
            $this->only(['nim', 'email', 'name', 'password', 'prodi', 'phone'])
        );

        session()->flash('success', 'Akun Berhasil Dibuat Silakan Login.');

        $this->redirect('/login');
    }
};
?>

<div class="min-h-screen">
    <flux:header container
        class="sticky top-0 bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:brand href="/" wire:navigate logo="https://fluxui.dev/img/demo/logo.png" name="University"
            class="max-lg:hidden dark:hidden" />
        <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="University"
            class="max-lg:hidden! hidden dark:flex" />
        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="home" href="/" wire:navigate x-bind:data-current="active === '#home'">
                Beranda
            </flux:navbar.item>
        </flux:navbar>
        <flux:spacer />
        <flux:navbar class="me-4">
            <flux:navbar.item class="bg-lime-400! text-zinc-900!">
                <a href="/daftar" wire:navigate>Daftar</a>
            </flux:navbar.item>
            <flux:navbar.item class="border! border-lime-400! text-zinc-900! hover:bg-lime-400!">
                <a href="/login" wire:navigate>Login</a>
            </flux:navbar.item>
        </flux:navbar>
    </flux:header>
    <flux:sidebar sticky collapsible="mobile"
        class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand href="#" logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png" name="University" />
            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="#" current>Home</flux:sidebar.item>
            <flux:sidebar.item icon="inbox" badge="12" href="#">Inbox</flux:sidebar.item>
            <flux:sidebar.item icon="document-text" href="#">Documents</flux:sidebar.item>
            <flux:sidebar.item icon="calendar" href="#">Calendar</flux:sidebar.item>
            <flux:sidebar.group expandable heading="Favorites" class="grid">
                <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>
        <flux:sidebar.spacer />
        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
        </flux:sidebar.nav>
    </flux:sidebar>

    <div class="mt-auto mx-4 sm:mx-20">
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
