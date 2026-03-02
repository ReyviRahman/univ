<?php

use Livewire\Component;
use App\Models\Faculty;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

new #[Layout('layouts::admin')] class extends Component
{
    public Faculty $faculty;

    public $code = '';
    public $name = '';

    public function mount(Faculty $faculty) {
        $this->faculty = $faculty;

        $this->code = $faculty->code;
        $this->name = $faculty->name;
    }

    protected function rules() {
        return [
            'code' => ['required', 'string', Rule::unique('faculties')->ignore($this->faculty)],
            'name' => ['required', 'string', Rule::unique('faculties')->ignore($this->faculty)],
        ];
    }

    public function save() {
        $this->validate();

        $this->faculty->update([
            'code' => $this->code,
            'name' => $this->name,
        ]);

        session()->flash('success', 'Data fakultas berhasil diperbarui.');

        $this->redirect(route('admin.fakultas.index'), navigate: true);
    }

};
?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.fakultas.index') }}" wire:navigate >Fakultas</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Fakultas</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <flux:heading class='text-center' size='xl' level='3' >Edit Fakultas</flux:heading>
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
                <flux:button variant="primary" class="w-full" type='submit' >Update</flux:button>
            </div>
        </flux:card>
    </form>
</div>