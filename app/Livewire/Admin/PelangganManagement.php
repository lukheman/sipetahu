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
    public $email = '';
    public $password = '';
    public $alamat = '';

    public $isEditMode = false;
    public $deleteId = null;
    public $showDeleteModal = false;

    protected function rules()
    {
        $rules = [
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ];

        if ($this->isEditMode) {
            $rules['email'] = 'required|email|unique:pelanggan,email,' . $this->pelangganId . ',id_pelanggan';
            $rules['password'] = 'nullable|string|min:8';
        } else {
            $rules['email'] = 'required|email|unique:pelanggan,email';
            $rules['password'] = 'required|string|min:8';
        }

        return $rules;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetInputFields()
    {
        $this->pelangganId = null;
        $this->nama_pelanggan = '';
        $this->no_hp = '';
        $this->email = '';
        $this->password = '';
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
            'email' => $this->email,
            'password' => \Illuminate\Support\Facades\Hash::make($this->password),
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
        $this->email = $pelanggan->email;
        $this->alamat = $pelanggan->alamat;
        
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->pelangganId) {
            $pelanggan = Pelanggan::findOrFail($this->pelangganId);
            
            $data = [
                'nama_pelanggan' => $this->nama_pelanggan,
                'no_hp' => $this->no_hp,
                'email' => $this->email,
                'alamat' => $this->alamat,
            ];

            if (!empty($this->password)) {
                $data['password'] = \Illuminate\Support\Facades\Hash::make($this->password);
            }

            $pelanggan->update($data);

            session()->flash('success', 'Pelanggan berhasil diperbarui!');
            $this->resetInputFields();
            $this->dispatch('close-modal');
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
    }

    public function delete()
    {
        if ($this->deleteId) {
            Pelanggan::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'Pelanggan berhasil dihapus!');
            $this->cancelDelete();
        }
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
