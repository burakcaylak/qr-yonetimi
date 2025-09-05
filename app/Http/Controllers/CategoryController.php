<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Kategori eklendi.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$category->id],
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Kategori güncellendi.');
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete(); // migrate'te restrictOnDelete olduğu için ürünü olan kategori silinemez
            return redirect()->route('categories.index')->with('success', 'Kategori silindi.');
        } catch (QueryException $e) {
            return back()->withErrors('Bu kategoriye bağlı ürünler olduğu için silinemez.');
        }
    }
}
