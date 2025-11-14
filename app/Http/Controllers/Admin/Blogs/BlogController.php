<?php

namespace App\Http\Controllers\Admin\Blogs;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $blogs = Blog::with('category')->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();
        
        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $blog = Blog::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'] ?? '',
            'category_id' => $validated['category_id'] ?? null,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Blog post created.'),
            'blog' => $blog->load('category')
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $blog = Blog::findOrFail($id);
        
        $blog->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'] ?? '',
            'category_id' => $validated['category_id'] ?? null,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' && !$blog->published_at ? now() : $blog->published_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Blog post updated.'),
            'blog' => $blog->load('category')
        ]);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => __('Blog post deleted.')
        ]);
    }
}
