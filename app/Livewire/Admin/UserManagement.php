<?php

namespace App\Livewire\Admin;

use App\Enums\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Manajemen Pengguna')]
class UserManagement extends Component
{
    use WithPagination;

    // Search
    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = '';

    // State
    public ?int $editingUserId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingUserId = null;

    protected function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'in:admin,pemilik'],
        ];

        if ($this->editingUserId) {
            $table = $this->role === 'admin' ? 'admin' : 'pemilik';
            $pk = $this->role === 'admin' ? 'id_admin' : 'id_pemilik';
            $rules['email'][] = 'unique:'.$table.',email,' . $this->editingUserId . ',' . $pk;
            if ($this->password) {
                $rules['password'] = ['confirmed', Password::defaults()];
            }
        } else {
            $table = $this->role === 'admin' ? 'admin' : 'pemilik';
            $rules['email'][] = 'unique:'.$table.',email';
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        return $rules;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingUserId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $userId, string $role): void
    {
        if ($role === 'admin') {
            $user = \App\Models\Admin::findOrFail($userId);
        } else {
            $user = \App\Models\Pemilik::findOrFail($userId);
        }

        $this->editingUserId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $role;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingUserId) {
            if ($this->role === 'admin') {
                $user = \App\Models\Admin::findOrFail($this->editingUserId);
            } else {
                $user = \App\Models\Pemilik::findOrFail($this->editingUserId);
            }

            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }

            $user->save();
            session()->flash('success', 'User updated successfully.');
        } else {
            if ($validated['role'] === 'admin') {
                \App\Models\Admin::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);
            } else {
                \App\Models\Pemilik::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);
            }
            session()->flash('success', 'User created successfully.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function confirmDelete(int $userId, string $role): void
    {
        $this->deletingUserId = $userId;
        $this->role = $role; // reuse role for deletion
        $this->showDeleteModal = true;
    }

    public function deleteUser(): void
    {
        if ($this->deletingUserId) {
            if ($this->role === 'admin') {
                \App\Models\Admin::destroy($this->deletingUserId);
            } else {
                \App\Models\Pemilik::destroy($this->deletingUserId);
            }
            session()->flash('success', 'User deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->deletingUserId = null;
        $this->role = '';
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingUserId = null;
        $this->role = '';
    }

    protected function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->editingUserId = null;
    }

    public function render()
    {
        $admins = \App\Models\Admin::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->get()->map(function($a) {
                return (object)[
                    'id' => $a->id_admin,
                    'name' => $a->name,
                    'email' => $a->email,
                    'role_name' => 'admin',
                    'created_at' => $a->created_at,
                    'initials' => method_exists($a, 'initials') ? $a->initials() : substr($a->name, 0, 2),
                ];
            });

        $pemiliks = \App\Models\Pemilik::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->get()->map(function($p) {
                return (object)[
                    'id' => $p->id_pemilik,
                    'name' => $p->name,
                    'email' => $p->email,
                    'role_name' => 'pemilik',
                    'created_at' => $p->created_at,
                    'initials' => method_exists($p, 'initials') ? $p->initials() : substr($p->name, 0, 2),
                ];
            });

        // Collect and paginate manually
        $allUsers = collect($admins)->merge($pemiliks)->sortByDesc('created_at')->values();

        // Simple manual pagination
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $allUsers->forPage($page, $perPage),
            $allUsers->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('livewire.admin.user-management', [
            'users' => $paginatedItems,
        ]);
    }
}
