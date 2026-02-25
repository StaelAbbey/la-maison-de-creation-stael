<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Str;
use Intervetion\image\Laravel\Facades\Image;
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
        $brand->slug = str_slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = carbon::new()->timestamp().'.'.$file_extention;
        $this->GeneracteBrandTumballsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand has been added successfully.');
    }

    public function GeneracteBrandTumballsImage($image, $imageName)
    {
     $destinationPath = public_path('uploads/brands');
     $img = Image::read($image->Path);
     $img->cover(124,124,"top");
     $img->resize(124,124,function ($constraint) {
         $constraint->aspectRatio();
     })->save($destinationPath.'/'.$imageName);
      
    }
}