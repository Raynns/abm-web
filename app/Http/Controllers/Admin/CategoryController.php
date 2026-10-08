<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
 public function index(): View { return view('admin.categories.index',['categories'=>Category::withCount('products')->orderBy('name')->get()]); }
 public function create(): View { return view('admin.categories.form',['category'=>new Category()]); }
 public function store(Request $request): RedirectResponse { $data=$this->validated($request); $data['slug']=Str::slug($data['name']); Category::create($data); return redirect()->route('admin.categories.index')->with('success','Kategori berhasil ditambahkan.'); }
 public function edit(Category $category): View { return view('admin.categories.form',compact('category')); }
 public function update(Request $request,Category $category): RedirectResponse { $data=$this->validated($request); $data['slug']=Str::slug($data['name']); $category->update($data); return redirect()->route('admin.categories.index')->with('success','Kategori berhasil diperbarui.'); }
 public function destroy(Category $category): RedirectResponse { if($category->products()->exists()) return back()->with('error','Kategori tidak dapat dihapus karena masih digunakan oleh produk.'); $category->delete(); return back()->with('success','Kategori berhasil dihapus.'); }
 private function validated(Request $request): array { return $request->validate(['name'=>['required','string','max:100']]); }
}
