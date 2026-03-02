<?php

use Livewire\Component;
use Livewire\Attributes\Validate;

new class extends Component {
    // State untuk form "Dapatkan Brosur"
    #[Validate('required|email', message: 'Masukkan email yang valid ya.')]
    public $email = '';

    public $isSubmitted = false;

    public function downloadBrosur()
    {
        $this->validate();

        // Simulasi proses backend (misal: kirim email atau simpan ke DB)
        // Mail::to($this->email)->send(new BrochureMail());

        $this->isSubmitted = true;

        // Reset form setelah sukses
        $this->reset('email');
    }
};
?>

<div class="font-sans text-slate-800 bg-slate-50"
    x-data="{ active: '#home' }" >
    <flux:header container
        class="sticky top-0 bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="University"
            class="max-lg:hidden dark:hidden" />
        <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="University"
            class="max-lg:hidden! hidden dark:flex" />
        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="home" href="#home" @click="active = '#home'"
                x-bind:data-current="active === '#home'">
                Beranda
            </flux:navbar.item>

            <flux:navbar.item icon="document-text" href="#prodi" @click="active = '#prodi'"
                x-bind:data-current="active === '#prodi'">
                Program Studi
            </flux:navbar.item>

            <flux:navbar.item icon="calendar" href="#beasiswa" @click="active = '#beasiswa'"
                x-bind:data-current="active === '#beasiswa'">
                Beasiswa
            </flux:navbar.item>
        </flux:navbar>
        <flux:spacer />
        <flux:navbar class="me-4">
            <flux:navbar.item href="/daftar" wire:navigate class="bg-lime-400! text-zinc-900!">
                Daftar
            </flux:navbar.item>
            <flux:navbar.item href="/login" wire:navigate class="border! border-lime-400! text-zinc-900! hover:bg-lime-400!">
            
                Login
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
    <header id="home" x-intersect.threshold.0.5="active = '#home'" class="relative bg-slate-900 text-white py-24 lg:py-32 overflow-hidden">
        <div
            class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center">
        </div>
        <div class="absolute inset-0 bg-linear-to-t from-slate-900 via-transparent to-transparent"></div>

        <div class="container mx-auto px-6 relative  text-center">
            <span
                class="inline-block py-1 px-3 rounded-full bg-amber-500/20 text-amber-400 text-sm font-semibold mb-6 border border-amber-500/30">
                Penerimaan Mahasiswa Baru 2024/2025
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">
                Membangun Masa Depan <br>
                <span class="text-transparent bg-clip-text bg-linear-to-r from-amber-400 to-orange-500">
                    Bersama Teknologi & Inovasi
                </span>
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto mb-10">
                Bergabunglah dengan Universitas. Kampus modern dengan kurikulum internasional yang siap mencetak
                pemimpin masa depan.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button
                    class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold py-3 px-8 rounded-full transition transform hover:scale-105 shadow-lg shadow-amber-500/20">
                    Daftar Online
                </button>
                <button
                    class="border border-slate-600 hover:border-white hover:bg-white/5 text-white font-medium py-3 px-8 rounded-full transition">
                    Lihat Program Studi
                </button>
            </div>
        </div>
    </header>

    <section class="py-10 bg-white border-b border-slate-100">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-slate-100">
                <div>
                    <div class="text-3xl font-bold text-slate-900">A</div>
                    <div class="text-sm text-slate-500 mt-1">Akreditasi Unggul</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-slate-900">50+</div>
                    <div class="text-sm text-slate-500 mt-1">Mitra Industri</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-slate-900">90%</div>
                    <div class="text-sm text-slate-500 mt-1">Lulusan Bekerja Cepat</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-slate-900">12</div>
                    <div class="text-sm text-slate-500 mt-1">Fakultas Pilihan</div>
                </div>
            </div>
        </div>
    </section>

    <section id="prodi" x-intersect.threshold.0.5="active = '#prodi'" class="py-20 bg-slate-50">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Pilih Jalur Masa Depanmu</h2>
                <p class="text-slate-600">Kami menawarkan berbagai program studi yang relevan dengan kebutuhan industri
                    digital saat ini.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 group">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Teknologi & Informatika</h3>
                    <p class="text-slate-500 mb-4 text-sm">Rekayasa Perangkat Lunak, Data Science, Cyber Security.</p>
                    <a href="#" class="text-blue-600 font-semibold text-sm hover:underline">Lihat Kurikulum
                        →</a>
                </div>

                <div
                    class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 group">
                    <div
                        class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 mb-6 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Bisnis & Ekonomi Digital</h3>
                    <p class="text-slate-500 mb-4 text-sm">Manajemen Bisnis, Akuntansi Digital, Kewirausahaan.</p>
                    <a href="#" class="text-amber-600 font-semibold text-sm hover:underline">Lihat Kurikulum
                        →</a>
                </div>

                <div
                    class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 group">
                    <div
                        class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Sains & Kesehatan</h3>
                    <p class="text-slate-500 mb-4 text-sm">Farmasi, Bioteknologi, Gizi, Kesehatan Masyarakat.</p>
                    <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline">Lihat Kurikulum
                        →</a>
                </div>
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 group">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Teknologi & Informatika</h3>
                    <p class="text-slate-500 mb-4 text-sm">Rekayasa Perangkat Lunak, Data Science, Cyber Security.</p>
                    <a href="#" class="text-blue-600 font-semibold text-sm hover:underline">Lihat Kurikulum
                        →</a>
                </div>

                <div
                    class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 group">
                    <div
                        class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 mb-6 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Bisnis & Ekonomi Digital</h3>
                    <p class="text-slate-500 mb-4 text-sm">Manajemen Bisnis, Akuntansi Digital, Kewirausahaan.</p>
                    <a href="#" class="text-amber-600 font-semibold text-sm hover:underline">Lihat Kurikulum
                        →</a>
                </div>

                <div
                    class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 group">
                    <div
                        class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Sains & Kesehatan</h3>
                    <p class="text-slate-500 mb-4 text-sm">Farmasi, Bioteknologi, Gizi, Kesehatan Masyarakat.</p>
                    <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline">Lihat Kurikulum
                        →</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-slate-900 text-white" x-intersect.threshold.0.5="active = '#beasiswa'" id="beasiswa">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="md:w-1/2">
                <h2 class="text-3xl font-bold mb-4">Ingin Tahu Lebih Lanjut?</h2>
                <p class="text-slate-400 mb-6">
                    Dapatkan brosur digital lengkap tentang rincian biaya, kurikulum, dan beasiswa langsung ke email
                    Anda.
                </p>
                <ul class="space-y-3 text-slate-300">
                    <li class="flex items-center gap-2"><span class="text-amber-500">✓</span> Rincian Biaya Kuliah
                    </li>
                    <li class="flex items-center gap-2"><span class="text-amber-500">✓</span> Info Beasiswa Potongan
                        50%</li>
                    <li class="flex items-center gap-2"><span class="text-amber-500">✓</span> Kalender Akademik</li>
                </ul>
            </div>

            <div class="md:w-1/2 w-full bg-slate-800 p-8 rounded-2xl border border-slate-700">
                @if ($isSubmitted)
                    <div class="text-center py-8 animate-pulse">
                        <div class="text-5xl mb-4">🎉</div>
                        <h3 class="text-xl font-bold text-white mb-2">Terima Kasih!</h3>
                        <p class="text-slate-400">Brosur telah dikirim ke <span
                                class="text-amber-400">{{ $email }}</span>.</p>
                        <button wire:click="$set('isSubmitted', false)"
                            class="mt-6 text-sm text-slate-500 hover:text-white underline">
                            Kirim ulang
                        </button>
                    </div>
                @else
                    <form wire:submit="downloadBrosur">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Alamat Email Anda</label>
                        <div class="relative">
                            <input type="email" wire:model="email"
                                class="w-full bg-slate-900 border border-slate-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition"
                                placeholder="nama@email.com">
                            <div wire:loading wire:target="downloadBrosur" class="absolute right-3 top-3.5">
                                <svg class="animate-spin h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                        @enderror

                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full mt-4 bg-linear-to-r from-amber-500 to-orange-500 text-white font-bold py-3 px-4 rounded-lg hover:from-amber-400 hover:to-orange-400 transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="downloadBrosur">Kirim Brosur Sekarang</span>
                            <span wire:loading wire:target="downloadBrosur">Mengirim...</span>
                        </button>
                        <p class="text-xs text-slate-500 mt-4 text-center">Data Anda aman. Kami tidak mengirim spam.
                        </p>
                    </form>
                @endif
            </div>
        </div>
    </section>

    <footer class="bg-slate-950 text-slate-400 py-12 border-t border-slate-900">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2024 Universitas Indonesia.</p>
        </div>
    </footer>
    {{-- <nav class="sticky top-0 z-50 bg-slate-900/95 backdrop-blur shadow-lg text-white">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold tracking-wider text-amber-400">
                U <span class="text-white font-light">University</span>
            </a>
            <div class="hidden md:flex space-x-8 items-center text-sm font-medium">
                <a href="#home" class="hover:text-amber-400 transition">Beranda</a>
                <a href="#prodi" class="hover:text-amber-400 transition">Program Studi</a>
                <a href="#beasiswa" class="hover:text-amber-400 transition">Beasiswa</a>
                <button class="bg-amber-500 text-slate-900 px-5 py-2 rounded-full hover:bg-amber-400 transition font-bold">
                    Daftar Sekarang
                </button>
            </div>
        </div>
    </nav> --}}


</div>
