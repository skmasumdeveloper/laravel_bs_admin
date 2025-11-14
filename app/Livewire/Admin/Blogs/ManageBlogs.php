<?php

namespace App\Livewire\Admin\Blogs;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ManageBlogs extends Component
{
    use WithPagination;

    public string $title = '';
    public string $content = '';
    public ?int $category_id = null;
    public string $status = 'draft';
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
        ];
    }

    public function mount(): void
    {
        // nothing for now; properties set by user interactions
    }

    public function getBlogsProperty()
    {
        return Blog::with('category')->latest()->paginate(10);
    }

    public function openCreate(): void
    {
        $this->resetForm();
    }

    public function createBlog(): void
    {
        $this->validate();

        $blog = Blog::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'content' => $this->content,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'published_at' => $this->status === 'published' ? now() : null,
        ]);

        Session::flash('success', __('Blog post created.'));
        $this->resetForm();
        $this->dispatch('$refresh');
    }

    public function openEdit(int $id): void
    {
        $blog = Blog::findOrFail($id);
        $this->editingId = $blog->id;
        $this->title = $blog->title;
        $this->content = $blog->content ?? '';
        $this->category_id = $blog->category_id;
        $this->status = $blog->status;
    }

    public function updateBlog(): void
    {
        $this->validate();

        $blog = Blog::find($this->editingId);
        if (! $blog) {
            Session::flash('error', __('Blog post not found.'));
            return;
        }

        $blog->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'content' => $this->content,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'published_at' => $this->status === 'published' && !$blog->published_at ? now() : $blog->published_at,
        ]);

        Session::flash('success', __('Blog post updated.'));
        $this->resetForm();
        $this->editingId = null;
        $this->dispatch('$refresh');
    }

    public function deleteBlog(int $id): void
    {
        $blog = Blog::find($id);
        if (! $blog) {
            Session::flash('error', __('Blog post not found.'));
            return;
        }

        $blog->delete();

        Session::flash('success', __('Blog post deleted.'));
        $this->dispatch('$refresh');
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->content = '';
        $this->category_id = null;
        $this->status = 'draft';
        $this->editingId = null;
    }

    public function render()
    {
        return view('livewire.admin.blogs.manage-blogs', [
            'blogs' => $this->blogs,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}

