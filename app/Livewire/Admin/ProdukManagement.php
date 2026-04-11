<?php

namespace App\Livewire\Admin;

use App\Models\Produk;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Data Produk')]
class ProdukManagement extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public string $nama_produk = '';
    public string $harga = '';
    public string $deskripsi = '';

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'nama_produk' => ['required', 'string', 'max:255', 'in:Tahu,Tempe'],
            'harga' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.in' => 'Nama produk hanya boleh Tahu atau Tempe.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $record = Produk::findOrFail($id);
        $this->editingId = $id;
        $this->nama_produk = $record->nama_produk;
        $this->harga = (string) $record->harga;
        $this->deskripsi = $record->deskripsi ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $record = Produk::findOrFail($this->editingId);
            $record->update($validated);
            session()->flash('success', 'Data produk berhasil diperbarui.');
        } else {
            Produk::create($validated);
            session()->flash('success', 'Data produk berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Produk::destroy($this->deletingId);
            session()->flash('success', 'Data produk berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    protected function resetForm(): void
    {
        $this->nama_produk = '';
        $this->harga = '';
        $this->deskripsi = '';
        $this->editingId = null;
    }

    public function render()
    {
        $records = Produk::query()
            ->when(
                $this->search,
                fn($q) =>
                $q->where('nama_produk', 'like', '%' . $this->search . '%')
            )
            ->orderBy('id_produk', 'desc')
            ->paginate(10);

        return view('livewire.admin.produk-management', [
            'records' => $records,
        ]);
    }
}
