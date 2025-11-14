<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
        $allCategories = Category::orderBy('name')->get();
        
        return view('admin.categories.index', compact('categories', 'allCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Category created.'),
            'category' => $category->load('children')
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $category = Category::findOrFail($id);
        
        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Category updated.'),
            'category' => $category->load('children')
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Reassign children to the parent of the deleted category (or null)
        Category::where('parent_id', $category->id)->update(['parent_id' => $category->parent_id]);
        
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => __('Category deleted.')
        ]);
    }
}
