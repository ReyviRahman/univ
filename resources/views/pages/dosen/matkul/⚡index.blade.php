<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use App\Models\Subject;
use Livewire\Attributes\Computed;

new #[Layout('layouts::dosen')] class extends Component
{
    use WithPagination;

    #[Computed]
    public function subjects() {
        return Subject::latest()->paginate(10);
    }

    public function delete(Subject $subject) {
        $subject->delete();
        session()->flash('success', 'Mata Kuliah berhasil dihapus.');
    }
};
?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item>Mata Kuliah</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class='text-center' size='xl' level='3'>Mata Kuliah Prodi {{ auth()->user()->lecturer->department->name }}</flux:heading>
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
        <a href="{{ route('dosen.matkul.create') }}" class="border-accent border text-sm px-4 py-2 rounded-md" wire:navigate >Tambah Mata Kuliah</a>
    </div>

    <flux:table class='border border-accent rounded-md mt-5'>
        <flux:table.columns class="border-accent!">
            <flux:table.column class=" border-accent! ps-2!">No</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Kode Mata Kuliah</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Nama Mata Kuliah</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Jumlah SKS</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Semester</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Status</flux:table.column>
            <flux:table.column class=" border-accent! ps-4!">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->subjects as $subject)
            <flux:table.row>
                <flux:table.cell class="ps-2!">
                    {{ $this->subjects->firstItem() + $loop->index }}
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge size="sm" color="zinc">{{ $subject->code }}</flux:badge>
                </flux:table.cell>
                <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $subject->name }}
                </flux:table.cell>
                <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $subject->sks }}
                </flux:table.cell>
                <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $subject->semester }}
                </flux:table.cell>
                <flux:table.cell>
                    @if($subject->is_active)
                        <flux:badge color="success" size="sm">Aktif</flux:badge>
                    @else
                        <flux:badge color="danger" size="sm">Tidak Aktif</flux:badge>
                    @endif
                </flux:table.cell>
                <flux:table.cell>
                    <flux:button size='sm' icon='pencil' href="{{ route('dosen.matkul.edit', $subject->id) }}" wire:navigate />
                    <flux:button size='sm' icon='trash' wire:click='delete({{ $subject->id }})' wire:confirm="Yakin mau hapus matkul {{ $subject->name }}?" />
                </flux:table.cell>
            </flux:table.row>

            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center text-zinc-500 py-6">
                        Belum ada data matkul.
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
        
    </flux:table>
    <flux:pagination :paginator="$this->subjects" />

</div>