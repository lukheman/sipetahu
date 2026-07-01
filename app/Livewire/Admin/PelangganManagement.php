<?php

namespace App\Livewire\Admin;

use App\Models\Pelanggan;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Manajemen Pelanggan')]
class PelangganManagement extends Component
{
    use WithPagination;

    public $search = '';
    
    public $pelangganId;
    public $nama_pelanggan = '';
    public $no_hp = '';
    public $alamat = '';

    public $isEditMode = false;

    protected $rules = [
        'nama_pelanggan' => 'required|string|max:255',
        'no_hp' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetInputFields()
    {
        $this->pelangganId = null;
        $this->nama_pelanggan = '';
        $this->no_hp = '';
        $this->alamat = '';
        $this->isEditMode = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        Pelanggan::create([
            'nama_pelanggan' => $this->nama_pelanggan,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
        ]);

        session()->flash('success', 'Pelanggan berhasil ditambahkan!');
        $this->resetInputFields();
        $this->dispatch('close-modal');
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $pelanggan = Pelanggan::findOrFail($id);
        
        $this->pelangganId = $pelanggan->id_pelanggan;
        $this->nama_pelanggan = $pelanggan->nama_pelanggan;
        $this->no_hp = $pelanggan->no_hp;
        $this->alamat = $pelanggan->alamat;
        
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->pelangganId) {
            $pelanggan = Pelanggan::findOrFail($this->pelangganId);
            $pelanggan->update([
                'nama_pelanggan' => $this->nama_pelanggan,
                'no_hp' => $this->no_hp,
                'alamat' => $this->alamat,
            ]);

            session()->flash('success', 'Pelanggan berhasil diperbarui!');
            $this->resetInputFields();
            $this->dispatch('close-modal');
        }
    }

    public function delete($id)
    {
        Pelanggan::findOrFail($id)->delete();
        session()->flash('success', 'Pelanggan berhasil dihapus!');
    }

    public function render()
    {
        $pelanggans = Pelanggan::where('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orderBy('id_pelanggan', 'desc')
            ->paginate(10);

        return view('livewire.admin.pelanggan-management', [
            'pelanggans' => $pelanggans
        ]);
    }
}
