<?php

namespace App\Livewire\Public;

use App\Models\Pelanggan;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Registrasi Pelanggan')]
class RegistrasiPelanggan extends Component
{
    public $nama_pelanggan = '';
    public $no_hp = '';
    public $alamat = '';

    public $successMessage = false;

    protected $rules = [
        'nama_pelanggan' => 'required|string|max:255',
        'no_hp' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
    ];

    public function submit()
    {
        $this->validate();

        Pelanggan::create([
            'nama_pelanggan' => $this->nama_pelanggan,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
        ]);

        $this->successMessage = true;
        $this->reset(['nama_pelanggan', 'no_hp', 'alamat']);
    }

    public function render()
    {
        return view('livewire.public.registrasi-pelanggan')->layout('layouts.guest');
    }
}
