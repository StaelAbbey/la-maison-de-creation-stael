@extends('layouts.admin')
@section('content')
 <div class="main-content-inner">
                            <!-- main-content-wrap -->
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>Add Produit</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{route('admin.index')}}">
                                                <div class="text-tiny">Dashboard</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                           <a href="{{ route('admin.produits') }}">
                                                <div class="text-tiny">Produits</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Edit Produit</div>
                                        </li>
                                    </ul>
                                </div>
                               <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('admin.produit.update') }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                                    <div class="wg-box">
                                        <fieldset class="name">
                                            <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                                            </div>
                                            <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0" value="{{ $produit->name }}" aria-required="true" required="">
                                            <div class="text-tiny">Do not exceed 100 characters when entering the
                                                product name.</div>
                                        </fieldset>
                                        @error('name')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                                        <fieldset class="name">
                                            <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                                            <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0" value="{{ $produit->slug }}" aria-required="true" required="">
                                            <div class="text-tiny">Do not exceed 100 characters when entering the
                                                product name.
                                            </div>
                                        </fieldset>
                                        @error('slug')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                                        <div class="gap22 cols">
                                            <fieldset class="category">
                                                <div class="body-title mb-10">Categorie <span class="tf-color-1">*</span>
                                                </div>
                                                <div class="select">
                                                    <select class="" name="category_id">
                                                        <option>Choose category</option>

                                                    @foreach($categories as $categorie)
                                                        <option value="{{ $categorie->id }}" {{ $produit->category_id == $categorie->id ? 'selected' : '' }}>{{ $categorie->name }}</option>
                                                    @endforeach
                                                
                                                    </select>
                                                </div>
                                            </fieldset>
                                            @error('category_id')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                            <fieldset class="brand">
                                                <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                                                </div>
                                                <div class="select">
                                                    <select class="" name="brand_id">
                                                        <option>Choose Brand</option>
                                                     
                                                        @foreach($brands as $brand)
                                                            <option value="{{ $brand->id }}" {{ $produit->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                        @endforeach
                                                       
                                                    </select>
                                                </div>
                                            </fieldset>
                                            @error('brand_id')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                        </div>

                                        <fieldset class="shortdescription">
                                            <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                                            <textarea class="mb-10 ht-150" name="short_description"placeholder="Short Description" tabindex="0" aria-required="true" required="">{{ old('short_description', $produit->short_description) }}</textarea>
                                            <div class="text-tiny">Do not exceed 100 characters when entering the
                                                product name.</div>
                                                @error('short_description')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                        </fieldset>

                                        <fieldset class="description">
                                            <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                                            </div>
                                            <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true" required="">{{ old('description', $produit->description) }}</textarea>
                                            <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                                        </fieldset>
                                        @error('description')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="wg-box">
                                        <fieldset>
                                            <div class="body-title">Upload images <span class="tf-color-1">*</span>
                                            </div>
                                            <div class="upload-image flex-grow">
                                                @if($produit->image)
                                                <div class="item" id="imgpreview">
                                                   <img src="{{ asset('uploads/produits/' . $produit->image) }}" class="effect8" alt="{{ $produit->name }}">
                                                </div>
                                                @endif
                                                <div id="upload-file" class="item up-load">
                                                    <label class="uploadfile" for="myFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                                        <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                                        <input type="file" id="myFile" name="image" accept="image/*">
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                        @error('image')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                                        <fieldset>
                                            <div class="body-title mb-10">Upload Gallery Images</div>
                                            <div class="upload-image mb-16">
                                                @if($produit->images)
                                                    @foreach(explode(',', $produit->images) as $image)
                                                        <div class="item gitems">
                                                            <img src="{{ asset('uploads/produits/' . $image) }}" alt="{{ $produit->name }}">
                                                        </div>
                                                    @endforeach  
                                                @endif                                              
                                                <div id="galUpload" class="item up-load">
                                                    <label class="uploadfile" for="gFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                                        <span class="text-tiny">Drop your images here or select <span
                                                                class="tf-color">click to browse</span></span>
                                                        <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                        @error('images')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                                        <div class="cols gap22">
                                            <fieldset class="name">
                                                <div class="body-title mb-10">Regular Price <span
                                                        class="tf-color-1">*</span></div>
                                                <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price" tabindex="0" value="{{$produit->regular_price}}" aria-required="true" required="">
                                            </fieldset>
                                            <fieldset class="name">
                                                <div class="body-title mb-10">Sale Price <span
                                                        class="tf-color-1">*</span></div>
                                                <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" tabindex="0" value="{{$produit->sale_price}}" aria-required="true" required="">
                                            </fieldset>
                                            @error('sale_price')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                        </div>


                                        <div class="cols gap22">
                                            <fieldset class="name">
                                                <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                                                </div>
                                                <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0" value="{{$produit->SKU}}" aria-required="true" required="">
                                            </fieldset>
                                            @error('SKU')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                            <fieldset class="name">
                                                <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span>
                                                </div>
                                                <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" tabindex="0" value="{{$produit->quantity}}" aria-required="true" required="">
                                            </fieldset>
                                            @error('quantity')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="cols gap22">
                                            <fieldset class="name">
                                                <div class="body-title mb-10">Stock</div>
                                                <div class="select mb-10">
                                                    <select class="" name="stock_status">
                                                        <option value="instock" {{ $produit->stock_status == 'instock' ? 'selected' : '' }}>InStock</option>
                                                        <option value="outofstock" {{ $produit->stock_status == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                                                    </select>
                                                </div>
                                            </fieldset>
                                            @error('stock_status')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                            <fieldset class="name">
                                                <div class="body-title mb-10">Featured</div>
                                                <div class="select mb-10">
                                                    <select class="" name="featured">
                                                        <option value="0" {{ $produit->featured == 0 ? 'selected' : '' }}>No</option>
                                                        <option value="1" {{ $produit->featured == 1 ? 'selected' : '' }}>Yes</option>
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="cols gap10">
                                            <button class="tf-button w-full" type="submit">Update product</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /form-add-product -->
                            </div>
                            <!-- /main-content-wrap -->
                        </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#myFile').on("change",function(e) {
                const photoInp = $("#myfile");
                 const[file] = this.files;
                 if(file)
                 {
                    $("#imgpreview img").attr('src' ,URL.createObjectURl(file));
                    $("#imgpreview").show();
                 }
                 });

                 $('#gFile').on("change",function(e) {
                const photoInp = $("#gfile");
                 const gphotos = this.files;
                 $.each(gphotos,function(key,val){
                    $("#galUpload").prepend('<div class="item gitems"><img src="${URL.createObjectURL(val)}"> </div>'
                        
                        
                        
                    );
                 })

                 });
                 
                 $("input[name='name]").on("change",function(){
                    $("input[name='slug']").val()
                  
                 });
                 });

                 function StringToSlug(Text)
                 {
                    return Text.toLowerCase()
                    .replace(/[^\w ]+/g,"")
                      .replace(/ +/g,"-");
                 }

    </script>
     @endpush