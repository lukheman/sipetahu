<?php

namespace App\Livewire\Pelanggan;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Riwayat Pembelian')]
class RiwayatPembelian extends Component
{
    public function render()
    {
        $penjualan = \App\Models\DataPenjualan::where('id_pelanggan', auth('pelanggan')->id())
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('livewire.pelanggan.riwayat-pembelian', [
            'penjualans' => $penjualan
        ])->layout('layouts.app');
    }
}
