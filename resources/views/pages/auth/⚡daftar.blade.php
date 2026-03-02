<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;

new class extends Component {
    
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

    <div class="mt-auto mx-4 sm:mx-20" x-data="{ activeTab: 'student'}">
        {{-- Header Judul --}}
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Registrasi Pengguna Baru</h1>
            <p class="text-gray-500">Silakan pilih jenis pengguna yang ingin didaftarkan</p>
        </div>

        {{-- BAGIAN TABS --}}
        <div class="flex justify-center mb-6">
            <div class="bg-white p-1 rounded-lg shadow-sm border inline-flex">

                {{-- Tombol Tab Mahasiswa --}}
                <button @click="activeTab = 'student'"
                    :class="activeTab === 'student' ? 'bg-lime-400 text-white shadow-sm' : 'text-gray-600 hover:bg-lime-300' "
                    class="px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    Mahasiswa Baru

                </button>

                {{-- Tombol Tab Dosen --}}
                <button @click="activeTab = 'lecturer'"
                    :class="activeTab === 'lecturer' ? 'bg-lime-400 text-white shadow-sm' : 'text-gray-600 hover:bg-lime-300'"
                    class="ml-1 px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    Dosen Baru
                </button>
            </div>
        </div>

        {{-- BAGIAN KONTEN FORM --}}
        <div>

            {{-- Logika Switching Component --}}
            <div x-show="activeTab === 'student'" x-transition.opacity>
                {{-- Kita load langsung, tapi disembunyikan jika tab bukan student --}}
                <livewire:register.mahasiswa />
            </div>

            {{-- Form Dosen --}}
            <div x-show="activeTab === 'lecturer'" x-transition.opacity style="display: none;">
                <livewire:register.dosen />
            </div>

        </div>
    </div>
</div>