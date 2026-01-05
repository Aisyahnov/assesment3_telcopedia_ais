<?php

namespace App\Policies;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChatMessage $chatMessage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChatMessage $message): bool
    {
        // Hanya pengirim asli yang boleh edit pesannya sendiri
        return $user->id === $message->sender_id;
    }

    /**
     * Tentukan apakah user bisa menghapus pesan ini.
     */
    public function delete(User $user, ChatMessage $message): bool
    {
        // Hanya pengirim asli yang boleh hapus pesannya sendiri
        return $user->id === $message->sender_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChatMessage $chatMessage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChatMessage $chatMessage): bool
    {
        return false;
    }
}
