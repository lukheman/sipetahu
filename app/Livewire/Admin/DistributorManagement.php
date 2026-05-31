<?php

namespace App\Livewire\Admin;

use App\Models\Distributor;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Manajemen Distributor')]
class DistributorManagement extends Component
{
    use WithPagination;

    public $search = '';
    
    public $distributorId;
    public $nama_distributor = '';
    public $no_hp = '';
    public $alamat = '';

    public $isEditMode = false;

    protected $rules = [
        'nama_distributor' => 'required|string|max:255',
        'no_hp' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetInputFields()
    {
        $this->distributorId = null;
        $this->nama_distributor = '';
        $this->no_hp = '';
        $this->alamat = '';
        $this->isEditMode = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        Distributor::create([
            'nama_distributor' => $this->nama_distributor,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
        ]);

        session()->flash('success', 'Distributor berhasil ditambahkan!');
        $this->resetInputFields();
        $this->dispatch('close-modal');
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $distributor = Distributor::findOrFail($id);
        
        $this->distributorId = $distributor->id_distributor;
        $this->nama_distributor = $distributor->nama_distributor;
        $this->no_hp = $distributor->no_hp;
        $this->alamat = $distributor->alamat;
        
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->distributorId) {
            $distributor = Distributor::findOrFail($this->distributorId);
            $distributor->update([
                'nama_distributor' => $this->nama_distributor,
                'no_hp' => $this->no_hp,
                'alamat' => $this->alamat,
            ]);

            session()->flash('success', 'Distributor berhasil diperbarui!');
            $this->resetInputFields();
            $this->dispatch('close-modal');
        }
    }

    public function delete($id)
    {
        Distributor::findOrFail($id)->delete();
        session()->flash('success', 'Distributor berhasil dihapus!');
    }

    public function render()
    {
        $distributors = Distributor::where('nama_distributor', 'like', '%' . $this->search . '%')
            ->orderBy('id_distributor', 'desc')
            ->paginate(10);

        return view('livewire.admin.distributor-management', [
            'distributors' => $distributors
        ]);
    }
}
