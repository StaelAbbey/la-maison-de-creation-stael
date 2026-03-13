<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Categorie;
use Carbon\Carbon; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Produit;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    // ==================== GESTION DES MARQUES ====================

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
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048', // nullable pour permettre l'absence d'image
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name); // on utilise le slug généré, pas celui du formulaire

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = now()->timestamp . '.' . $file_extension;
            $this->generateBrandThumbnail($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Marque ajoutée avec succès.');
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
            'slug' => 'required|unique:brands,slug,' . $request->id, // virgule avant l'ID
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand = Brand::find($request->id);
        if (!$brand) {
            return redirect()->back()->with('error', 'Marque introuvable.');
        }

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name); // même logique : slug généré à partir du nom

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($brand->image && file_exists(public_path('uploads/brands/' . $brand->image))) {
                File::delete(public_path('uploads/brands/' . $brand->image));
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = now()->timestamp . '.' . $file_extension;
            $this->generateBrandThumbnail($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Marque mise à jour avec succès.');
    }

    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            if ($brand->image && file_exists(public_path('uploads/brands/' . $brand->image))) {
                File::delete(public_path('uploads/brands/' . $brand->image));
            }
            $brand->delete();
        }
        return redirect()->route('admin.brands')->with('status', 'Marque supprimée avec succès.');
    }

    private function generateBrandThumbnail($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $img = Image::read($image->path());
        $img->cover(124, 124, 'top');
        $img->save($destinationPath . '/' . $imageName);
    }

    // ==================== GESTION DES CATÉGORIES ====================

    public function categories()
    {
        $categories = Categorie::orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function category_add()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'nullable|unique:categories,slug', // slug peut être null car on le génère automatiquement
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = new Categorie();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name); // génération automatique

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = now()->timestamp . '.' . $file_extension;
            $this->generateCategoryThumbnail($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Catégorie ajoutée avec succès.');
    }

    private function generateCategoryThumbnail($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $img = Image::read($image->path());
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->save($destinationPath . '/' . $imageName);
    }

    public function category_edit($id)
    {
        $category = Categorie::find($id);
        return view('admin.category-edit', compact('category'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'nullable|unique:categories,slug,' . $request->id, // virgule avant l'ID
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = Categorie::find($request->id);
        if (!$category) {
            return redirect()->back()->with('error', 'Catégorie introuvable.');
        }

        $category->name = $request->name;
        $category->slug = Str::slug($request->name); // même logique : slug généré à partir du nom

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
                File::delete(public_path('uploads/categories/' . $category->image));
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = now()->timestamp . '.' . $file_extension;
            $this->generateCategoryThumbnail($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Catégorie mise à jour avec succès.');
    }
    


public function category_delete($id)
{
    $category = Categorie::findOrFail($id);

    if (File::exists(public_path('uploads/categories/' . $category->image))) 
    {
        File::delete(public_path('uploads/categories/' . $category->image));
    }

    $category->delete();

    return redirect()->route('admin.categories')->with('status', 'Catégorie supprimée avec succès.');
}

public function produits()
{
    $produits = Produit::orderBy('created_at', 'DESC')->paginate(10);

    return view('admin.produits', compact('produits'));

}

public function produit_add()
{
    $categories = categorie::select('id','name')->orderBy('name')->get();
    $brands =Brand::select('id','name')->orderBy('name')->get();
    return view('admin.produit-add',compact('categories','brands'));
}

public function produit_store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:produits,slug',
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required',
        'sale_price' => 'required',
        'SKU' => 'required',
        'featured' => 'required',
        'quantity' => 'required',
        'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'category_id' => 'required',
        'brand_id' => 'required|exists:brands,id',
        
    ]);

    $produit = new Produit();
    $produit->name = $request->name;
    $produit->slug = Str::slug($request->name);
    $produit->short_description = $request->short_description;
    $produit->description = $request->description;
    $produit->regular_price = $request->regular_price;
    $produit->sale_price = $request->sale_price;
    $produit->SKU = $request->SKU;
    $produit->featured = $request->featured;
    $produit->quantity = $request->quantity;
    $produit->category_id = $request->category_id;
    $produit->brand_id = $request->brand_id;
  
        $currentDateTime = Carbon::now()->format('Y-m-d-H-i-s');
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = now()->timestamp . '.' . $file_extension;
        $produit->image = $file_name;
    }

    $gallery_arr = array();
    $gallery_images ="";
    $counter = 1;

    if($request->hasFile('images'))
        {
            $allowedfileExtension = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
            $files = $request->file('images');
            foreach($files as $file)
                {
                    $gextension = $file->getClientOriginalExtension();
                    $gcheck = in_array($gextension, $allowedfileExtension);
                    if($gcheck)
                    {
                        $gfileName = $currentDateTime.'-'.$counter.'.'.$gextension;
                        $this->generateProductThumbnail($file, $gfileName);
                        array_push($gallery_arr, $gfileName);
                        $counter = $counter + 1;
                       
                }
                }
                $gallery_images = implode(',', $gallery_arr);
        }
        $produit->images = $gallery_images;
        $produit->save();   
    

    return redirect()->route('admin.produits')->with('status', 'Produit ajouté avec succès.');

}

private function generateProductThumbnail($image, $imageName)
 {
        $destinationPathThumbnail = public_path('uploads/produits/thumbnails');
         $destinationPath = public_path('uploads/produits');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $img = Image::read($image->path());
        $img->cover(540, 699, 'top');
        $img->resize(540, 699,function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

         $img->resize(104, 104,function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail . '/' . $imageName);
        


      }

      public function produit_edit($id)
      {
          $produit = Produit::find($id);
          $categories = categorie::select('id','name')->orderBy('name')->get();
          $brands =Brand::select('id','name')->orderBy('name')->get();
          return view('admin.produit-edit', compact('produit','categories','brands'));
      }

         public function produit_update(Request $request)
         {
               $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:produits,slug,' . $request->id, // virgule avant l'ID
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required',
        'sale_price' => 'required',
        'SKU' => 'required',
        'featured' => 'required',
        'quantity' => 'required',
        'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        'category_id' => 'required',
        'brand_id' => 'required|exists:brands,id',
        
    ]);

   $produit = Produit::find($request->produit_id);
    $produit->name = $request->name;
    $produit->slug = Str::slug($request->name);
    $produit->short_description = $request->short_description;
    $produit->description = $request->description;
    $produit->regular_price = $request->regular_price;
    $produit->sale_price = $request->sale_price;
    $produit->SKU = $request->SKU;
    $produit->featured = $request->featured;
    $produit->quantity = $request->quantity;
    $produit->category_id = $request->category_id;
    $produit->brand_id = $request->brand_id;
  
    $currentDateTime = Carbon::now()->format('Y-m-d-H-i-s');

     if ($request->hasFile('image')) 
        { 
            if(File::exists(public_path('uploads/produits/' . $produit->image))) 
            {
                File::delete(public_path('uploads/produits/' . $produit->image));
            }
             if(File::exists(public_path('uploads/produits/thumbnails/' . $produit->image))) 
            {
                File::delete(public_path('uploads/produits/thumbnails/' . $produit->image));
            }

        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = now()->timestamp . '.' . $file_extension;
        $produit->image = $file_name;
    }

    $gallery_arr = array();
    $gallery_images ="";
    $counter = 1;

    if($request->hasFile('images'))
        {
          foreach(explode(',', $produit->images) as $ofile)
            {
               if(File::exists(public_path('uploads/produits/' . $ofile))) 
            {
                File::delete(public_path('uploads/produits/' . $ofile));
            }
             if(File::exists(public_path('uploads/produits/thumbnails/' .$ofile))) 
            {
                File::delete(public_path('uploads/produits/thumbnails/' . $ofile));
            }
            }

            $allowedfileExtension = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
            $files = $request->file('images');
            foreach($files as $file)
                {
                    $gextension = $file->getClientOriginalExtension();
                    $gcheck = in_array($gextension, $allowedfileExtension);
                    if($gcheck)
                    {
                        $gfileName = $currentDateTime.'-'.$counter.'.'.$gextension;
                        $this->generateProductThumbnail($file, $gfileName);
                        array_push($gallery_arr, $gfileName);
                        $counter = $counter + 1;
                       
                }
                }
                $gallery_images = implode(',', $gallery_arr);
                 $produit->images = $gallery_images;
        }
       
        $produit->save();   
        return redirect()->route('admin.produits')->with('status', 'Produit mis à jour avec succès.');


}

        public function produit_delete($id)
        {
            $produit = Produit::find($id);
            if(File::exists(public_path('uploads/produits/' . $produit->image))) 
            {
                File::delete(public_path('uploads/produits/' . $produit->image));
            }
             if(File::exists(public_path('uploads/produits/thumbnails/' . $produit->image))) 
            {
                File::delete(public_path('uploads/produits/thumbnails/' . $produit->image));
            }

             foreach(explode(',', $produit->images) as $ofile)
            {
               if(File::exists(public_path('uploads/produits/' . $ofile))) 
            {
                File::delete(public_path('uploads/produits/' . $ofile));
            }
             if(File::exists(public_path('uploads/produits/thumbnails/' .$ofile))) 
            {
                File::delete(public_path('uploads/produits/thumbnails/' . $ofile));
            }
            }
            $produit->delete();
            return redirect()->route('admin.produits')->with('status', 'Produit supprimé avec succès.');
        }


 }
