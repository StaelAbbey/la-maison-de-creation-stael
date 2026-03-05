<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Carbon\Carbon; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;


class AdminController extends Controller
{
   public function index()
    {
        return view('admin.index');
    }

    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }
    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug', 
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
         $file_extension = $image->extension();
       $file_name = now()->timestamp . '.' . $file_extension;
        $this->GeneracteBrandTumballsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand has been added successfully.');
    }
    
    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }
   
public function brand_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug'.$request->id, 
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        if ($request->hasFile('image')) {
            if(file_exists(public_path('uploads/brands/' . $brand->image))){
                File::delete(public_path('uploads/brands/' . $brand->image));
            }
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = now()->timestamp . '.' . $file_extension;
            $this->GeneracteBrandTumballsImage($image, $file_name);
            $brand->image = $file_name;
       }
        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand has been updated successfully.');
    }

   public function GeneracteBrandTumballsImage($image, $imageName)
{
    // Créer le dossier de destination s'il n'existe pas
    $destinationPath = public_path('uploads/brands');
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Charger l'image (path() en minuscules)
    $img = Image::read($image->path());

    // Redimensionner et recadrer pour obtenir exactement 124x124 pixels, aligné en haut
    $img->cover(124, 124, 'top');

    // Sauvegarder l'image
    $img->save($destinationPath . '/' . $imageName);
}

    
public function brand_delete($id)
{
    $brand = Brand::find($id);
    if(file_exists(public_path('uploads/brands/' . $brand->image))){
        File::delete(public_path('uploads/brands/' . $brand->image));
    }
    $brand->delete();
    return redirect()->route('admin.brands')->with('status', 'Brand has been deleted successfully.');
}
}