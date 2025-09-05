<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'category_id' => ['required','exists:categories,id'],
        'name'        => ['required','string','max:255'],
        'pdf'         => ['required','file','mimes:pdf','max:20480'],
    ]);

    // PDF'i kaydet
    $path = $request->file('pdf')->store('products','public');

    // SLUG ÜRET (benzersiz)
    $base = Str::slug($data['name']);
    $slug = $base;
    $i = 1;
    while (\App\Models\Product::where('slug', $slug)->exists()) {
        $slug = $base.'-'.$i++;
    }

    // Kayıt
    \App\Models\Product::create([
        'category_id' => $data['category_id'],
        'name'        => $data['name'],
        'pdf_path'    => $path,
        'slug'        => $slug,     // ← slug insert sırasında geliyor
    ]);

    return redirect()->route('products.index')->with('success','Ürün eklendi.');
}
    public function edit(Product $product)
    {
        // Adı ve kategori değişmeyecek -> formda disabled göstereceğiz
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Yalnızca PDF değişebilir
        $data = $request->validate([
            'pdf' => ['required','file','mimes:pdf','max:20480'],
        ]);

        // eski dosyayı silebilirsin (opsiyonel)
        if ($product->pdf_path && Storage::disk('public')->exists($product->pdf_path)) {
            Storage::disk('public')->delete($product->pdf_path);
        }

        $path = $request->file('pdf')->store('products','public');
        $product->update(['pdf_path' => $path]);

        return redirect()->route('products.index')->with('success','PDF güncellendi.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('product-delete');

        // pdf'i de temizlemek istersen:
        // if ($product->pdf_path) Storage::disk('public')->delete($product->pdf_path);

        $product->delete();
        return redirect()->route('products.index')->with('success','Ürün silindi.');
    }
}
