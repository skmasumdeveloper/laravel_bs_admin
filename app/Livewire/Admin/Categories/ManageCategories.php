<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class ManageCategories extends Component
{
    public string $name = '';
    public ?int $parent_id = null;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function mount(): void
    {
        // nothing for now; properties set by user interactions
    }

    public function getCategoriesProperty()
    {
        return Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
    }

    public function createCategory(): void
    {
        $this->validate();

        $category = Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'parent_id' => $this->parent_id,
        ]);

        Session::flash('success', __('Category created.'));
        $this->resetForm();
        $this->dispatch('$refresh');
    }

    public function openEdit(int $id): void
    {
        $c = Category::findOrFail($id);
        $this->editingId = $c->id;
        $this->name = $c->name;
        $this->parent_id = $c->parent_id;
    }

    public function updateCategory(): void
    {
        $this->validate();

        $cat = Category::find($this->editingId);
        if (! $cat) {
            Session::flash('error', __('Category not found.'));
            return;
        }

        $cat->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'parent_id' => $this->parent_id,
        ]);

        Session::flash('success', __('Category updated.'));
        $this->resetForm();
        $this->editingId = null;
        $this->dispatch('$refresh');
    }

    public function deleteCategory(int $id): void
    {
        $cat = Category::find($id);
        if (! $cat) {
            Session::flash('error', __('Category not found.'));
            return;
        }

        // Reassign children to the parent of the deleted category (or null)
        Category::where('parent_id', $cat->id)->update(['parent_id' => $cat->parent_id]);

        $cat->delete();

        Session::flash('success', __('Category deleted.'));
        $this->dispatch('$refresh');
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->parent_id = null;
        $this->editingId = null;
    }

    public function render()
    {
        return view('livewire.admin.categories.manage-categories', [
            'categories' => $this->categories,
            'allCategories' => Category::orderBy('name')->get(),
        ]);
    }
}
