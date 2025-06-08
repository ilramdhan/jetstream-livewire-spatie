<?php

namespace App\Policies\Post;

use App\Models\Post\Posts;
use App\Models\User;

class PostPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->can('create-post');
    }

    public function update(User $user, Posts $post): bool
    {
        return $user->can('manage-all-posts') ||
            ($user->can('update-own-post') && $post->user_id === $user->id);
    }

    public function delete(User $user, Posts $post): bool
    {
        return $user->can('manage-all-posts') ||
            ($user->can('delete-own-post') && $post->user_id === $user->id);
    }
}
