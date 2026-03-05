<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    #[Validate('required')]
    public $username = '';

    #[Validate('required')]
    public $password = '';

    public function login() {
        $this->validate();

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            session()->regenerate();

            $role = Auth::user()->role;
            return match ($role) {
                'admin' => $this->redirect(route('admin.fakultas.index'), navigate:true),
                'lecturer' => $this->redirect(route('dosen.matkul.index'), navigate:true),
                'student' => $this->redirect(route(''), navigate:true),
            };
        }

        $this->addError('username', 'NIM/NIDN atau password salah.');
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


    <div class="flex items-center justify-center mt-auto ">
        <form wire:submit='login'>
            <flux:card class="space-y-6 min-w-96 mt-20 border-accent!">
                @if (session('success'))
                    <div 
                        x-data="{ show: true }"
                        x-init="setTimeout(() => show = false, 3000)"
                        x-show="show"
                        x-transition.duration.500ms
                        class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" 
                        role="alert"
                    >
                        <span class="font-medium">Berhasil!</span> {{ session('success') }}
                    </div>
                @endif
                <div>
                    <flux:heading size="lg">Log in to your account</flux:heading>
                    <flux:text class="mt-2">Welcome back!</flux:text>
                </div>
    
                <div class="space-y-6">
                    <flux:input label="Username" type="text" placeholder="Masukkan NIM/NIDN" wire:model='username' required />
    
                    <flux:field>
                        <div class="mb-3 flex justify-between">
                            <flux:label>Password</flux:label>
    
                            <flux:link href="#" variant="subtle" class="text-sm">Forgot password?</flux:link>
                        </div>
    
                        <flux:input type="password" placeholder="Your password" wire:model='password' required />
                        <flux:error name="password" />
                    </flux:field>
                </div>
    
                <div class="space-y-2">
                    <flux:button variant="primary" class="w-full" type='submit' >Log in</flux:button>
                    <flux:button variant="ghost" class="w-full">Sign up for a new account</flux:button>
                </div>
            </flux:card>
        </form>
    </div>
</div>
