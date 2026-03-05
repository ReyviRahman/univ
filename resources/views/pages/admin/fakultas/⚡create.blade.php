<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\Faculty;

new #[Layout('layouts::admin')] class extends Component
{
    #[Validate('required|string|unique:faculties,code', as: 'Kode Fakultas')]
    public $code = '';

    #[Validate('required|string|unique:faculties,name', as: 'Nama Fakultas')]
    public $name = '';

    public function save() {
        $this->validate();

        Faculty::create([
            'code' => $this->code,
            'name' => $this->name,
        ]);

        session()->flash('success', 'Fakultas Berhasil Ditambahkan');
        $this->redirect(route('admin.fakultas.index'), navigate:true);
    }
};
?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.fakultas.index') }}" wire:navigate >Fakultas</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Tambah Fakultas</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <flux:heading class='text-center' size='xl' level='3' >Tambah Fakultas</flux:heading>
    <form wire:submit='save'>
        <flux:card class="space-y-6 mt-4">
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
                <div class="space-y-6">
                    <flux:input label="Kode Fakultas" type="text" placeholder="Masukkan Kode Fakultas" wire:model='code' required/>
                </div>
                <div class="space-y-6">
                    <flux:input label="Nama Fakultas" type="text" placeholder="Masukkan Nama Fakultas" wire:model='name' required/>
                </div>
    
            </div>
            <div class="space-y-2">
                <flux:button variant="primary" class="w-full" type='submit' >Tambahkan</flux:button>
            </div>
        </flux:card>
    </form>
</div>