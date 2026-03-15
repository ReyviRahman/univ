<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AcademicPeriod;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;


new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    #[Computed]
    public function academicPeriods() {
        return AcademicPeriod::latest()->paginate(20);
    }

    public function delete(AcademicPeriod $academicPeriod) {
        $academicPeriod->delete();
        session()->flash('success', 'Periode Akademik berhasil dihapus.');
    }
};
?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item>Periode Akademik</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:heading class='text-center' size='xl' level='3'>Periode Akademik</flux:heading>

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
        <a href="{{ route('admin.academic-period.create') }}" class="border-accent border text-sm px-4 py-2 rounded-md" wire:navigate >Tambah Periode Akademik</a>
    </div>

    <flux:table class='border border-accent rounded-md mt-5'>
        <flux:table.columns class="border-accent!">
            <flux:table.column class=" border-accent! ps-2!">No</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Tahun Akademik</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Semester</flux:table.column>
            <flux:table.column class=" border-accent! ps-2!">Status</flux:table.column>
            <flux:table.column class=" border-accent! ps-4!">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->academicPeriods as $academicPeriod)
            <flux:table.row wire:key="academicPeriod-{{ $academicPeriod->id }}">
                <flux:table.cell class="ps-2!">
                    {{ $this->academicPeriods->firstItem() + $loop->index }}
                </flux:table.cell>
                <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $academicPeriod->academic_year }}
                </flux:table.cell>
                <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $academicPeriod->semester_type }}
                </flux:table.cell>
                <flux:table.cell>
                    @if($academicPeriod->is_active)
                        <flux:badge color="success" size="sm">Aktif</flux:badge>
                    @else
                        <flux:badge color="danger" size="sm">Tidak Aktif</flux:badge>
                    @endif
                </flux:table.cell>
                <flux:table.cell>
                    <flux:button size='sm' icon='pencil' href="{{ route('admin.academic-period.edit', $academicPeriod->id) }}" wire:navigate />
                    <flux:button size='sm' icon='trash' wire:click='delete({{ $academicPeriod->id }})' wire:confirm="Yakin mau hapus periode akademik {{ $academicPeriod->name }}?" />
                </flux:table.cell>
            </flux:table.row>

            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center text-zinc-500 py-6">
                        Belum ada periode akademik.
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
        
    </flux:table>
    <flux:pagination :paginator="$this->academicPeriods" />

    

    
</div>