<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Faculty;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    #[Computed]
    public function faculties() {
        return Faculty::latest()->paginate(10);
    }

    public function delete(Faculty $faculty) {
        $faculty->delete();
        session()->flash('success', 'Fakultas berhasil dihapus.');
    }

};
?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item>Fakultas</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <flux:heading class='text-center' size='xl' level='3'>Fakultas</flux:heading>
    @if (session('success'))
        <div 
            wire:key="{{ rand() }}" 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show" 
            x-transition.duration.500ms
            class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" 
            role="alert">
            
            <span class="font-medium">Berhasil! </span> {{ session('success') }}
        </div>
    @endif
    <div class="flex justify-end">
        <a href="{{ route('admin.fakultas.create') }}" class="border-accent border text-sm px-4 py-2 rounded-md" wire:navigate >Tambah Fakultas</a>
    </div>
    <flux:table class='border border-accent rounded-md mt-5'>
        <flux:table.columns class="border-accent!">
            <flux:table.column class=" border-accent! ps-2!">No</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Kode Fakultas</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Nama Fakultas</flux:table.column>
            <flux:table.column class=" border-accent! ps-4!">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->faculties as $faculty)
            <flux:table.row>
                <flux:table.cell class="ps-2!">
                    {{ $this->faculties->firstItem() + $loop->index }}
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge size="sm" color="zinc">{{ $faculty->code }}</flux:badge>
                </flux:table.cell>
                <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $faculty->name }}
                </flux:table.cell>
                <flux:table.cell>
                    <flux:button size='sm' icon='pencil' href="{{ route('admin.fakultas.edit', $faculty->id) }}" wire:navigate />
                    <flux:button size='sm' icon='trash' wire:click='delete({{ $faculty->id }})' wire:confirm="Yakin mau hapus fakultas {{ $faculty->name }}?" />
                </flux:table.cell>
            </flux:table.row>

            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center text-zinc-500 py-6">
                        Belum ada data fakultas.
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
        
    </flux:table>
    <flux:pagination :paginator="$this->faculties" />
</div>