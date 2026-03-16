<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function paginateWithSearch(?string $search)
    {
        return User::select('id', 'name', 'username', 'email', 'role', 'status', 'created_at')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);
    }

    public function findOrFail(int $id): User
    {
        return User::findOrFail($id);
    }

    public function toggleStatus(int $id): User
    {
        $user = $this->findOrFail($id);
        $user->status = $user->isAktif() ? 'nonaktif' : 'aktif';
        $user->save();

        return $user;
    }
}
