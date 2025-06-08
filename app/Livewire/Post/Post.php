<?php

namespace App\Livewire\Post;

use App\Models\Post\Posts;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Post extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public string $title = '';
    public string $content = '';
    public ?int $postId = null;
    public bool $isModalOpen = false;
    public bool $isConfirmDeleteOpen = false;

    protected function rules()
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
        ];
    }

    public function render()
    {
        $postsQuery = Posts::query();

        /*
         * Selain admin, user hanya dapat melihat post yg dibuatnya
         * Comment bagian if jika semua user dapat melihat post user lain
        */
        if (! auth()->user()->can('manage-all-posts')) {
            $postsQuery->where('user_id', auth()->id());
        }

        return view('livewire.post.post', [
            'posts' => $postsQuery->with('user')->latest()->paginate(5)
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->authorize('create', Posts::class);

        $this->resetForm();
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'content' => $this->content,
        ];

        if ($this->postId) {
            $post = Posts::findOrFail($this->postId);
            $this->authorize('update', $post);
            $post->update($data);
        } else {
            $this->authorize('create', Posts::class);
            $data['user_id'] = auth()->id();
            Posts::create($data);
        }

        session()->flash('message', $this->postId ? 'Post berhasil diperbarui.' : 'Post berhasil dibuat.');
        $this->closeModal();
        $this->resetForm();
    }

    public function edit(int $id)
    {
        $post = Posts::findOrFail($id);
        $this->authorize('update', $post);

        $this->postId = $id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->openModal();
    }

    public function confirmDelete(int $id)
    {
        $post = Posts::findOrFail($id);
        $this->authorize('delete', $post);

        $this->postId = $id;
        $this->isConfirmDeleteOpen = true;
    }

    public function delete()
    {
        if($this->postId) {
            $post = Posts::findOrFail($this->postId);
            $this->authorize('delete', $post);

            $post->delete();
            session()->flash('message', 'Post berhasil dihapus.');
        }
        $this->closeConfirmDeleteModal();
        $this->resetForm();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function closeConfirmDeleteModal()
    {
        $this->isConfirmDeleteOpen = false;
    }

    private function resetForm()
    {
        $this->postId = null;
        $this->title = '';
        $this->content = '';
        $this->resetErrorBag();
    }
}
