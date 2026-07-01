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
    public $email = '';
    public $password = '';
    public $password_confirmation = '';


    protected function rules()
    {
        return [
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'email' => 'required|email|unique:pelanggan,email',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function submit()
    {
        $this->validate();

        $pelanggan = Pelanggan::create([
            'nama_pelanggan' => $this->nama_pelanggan,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
            'email' => $this->email,
            'password' => \Illuminate\Support\Facades\Hash::make($this->password),
        ]);

        session()->flash('success', 'Registrasi berhasil! Silakan login menggunakan email dan password Anda.');
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.public.registrasi-pelanggan')
            ->layout('layouts.guest', ['type' => 'auth']);
    }
}
