<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Department;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    #[Computed]
    public function departments() {
        return Department::latest()->paginate(10);
    }

    public function delete(Department $department) {
        $department->delete();
        session()->flash('success', 'Program Studi Berhasil Dihapus');
    }

};
?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item>Program Studi</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class='text-center' size='xl' level='3'>Program Studi</flux:heading>

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
        <a href="{{ route('admin.prodi.create') }}" class="border-accent border text-sm px-4 py-2 rounded-md" wire:navigate >Tambah Program Studi</a>
    </div>

    <flux:table class='border border-accent rounded-md mt-5'>
        <flux:table.columns class="border-accent!">
            <flux:table.column class=" border-accent! ps-2!">No</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Kode Fakultas</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Kode Prodi</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Nama Prodi</flux:table.column>
            <flux:table.column class=" border-accent! ps-4!">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->departments as $department)
            <flux:table.row>
                <flux:table.cell class="ps-2!">
                    {{ $this->departments->firstItem() + $loop->index }}
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge size="sm" color="zinc">{{ $department->faculty->code }}</flux:badge>
                </flux:table.cell>
                <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                    <flux:badge size="sm" color="zinc">{{ $department->code }}</flux:badge>
                </flux:table.cell>
                <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $department->name }}
                </flux:table.cell>
                <flux:table.cell>
                    <flux:button size='sm' icon='pencil' href="{{ route('admin.prodi.edit', $department->id) }}" wire:navigate />
                    <flux:button size='sm' icon='trash' wire:click='delete({{ $department->id }})' wire:confirm="Yakin mau hapus prodi {{ $department->name }}?" />
                </flux:table.cell>
            </flux:table.row>

            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center text-zinc-500 py-6">
                        Belum ada data prodi.
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
        
    </flux:table>
    <flux:pagination :paginator="$this->departments" />

</div>