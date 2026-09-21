<!-- ===========================
| Edit Variant Modal Section |
=========================== -->
<?php
   $images = \App\Models\ProductGraphics::where('product_id', $product->id)->where('variant_id',$product->id)->where('graphic_type','image')->get();
   $videos = \App\Models\ProductGraphics::where('product_id', $product->id)->where('variant_id',$product->id)->where('graphic_type','video')->get();
   $imagePath = config('constant.PRODUCT_IMAGE_PATH'); 
?>
<div id="formErrorPopup" class="alert alert-danger d-none">
    <strong>⚠️ Please fix the following:</strong>
    <ul class="mb-0" id="formErrorList"></ul>
</div>

<div class="modal fade" id="editVariantModal" tabindex="-1" role="dialog" aria-labelledby="editVariantModalLabel"
    aria-hidden="true">
    <div class="modal-dialog-slideout" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background: #f5f2f2 !important;">
                <h5 class="modal-title" id="editVariantModalLabel">Edit Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="background: #f5f2f2 !important;">
                <input type="hidden" id="v_id" value="">
                <form id="edit_varient_form">
                    <div class="row">
                        <!-- Variant Name -->
                        <div class="col-12 mb-3">
                            <label for="v_name">Variant Name *</label>
                            <input type="text" class="form-control" name="v_name" id="v_name" placeholder="Variant Name">
                        </div>

                        <!-- SKU -->
                        <div class="col-12 mb-3">
                            <label for="v_sku">SKU *</label>
                            <input type="text" class="form-control" name="v_sku" id="v_sku" placeholder="Variant SKU">
                        </div>

                        <!-- Price & Selling Price -->
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="v_price">Price *</label>
                                    <input type="text" class="form-control" name="v_price" id="v_price" placeholder="Variant Price">
                                </div>
                                <div class="col-md-6">
                                    <label for="v_sprice">Selling Price *</label>
                                    <input type="text" class="form-control" name="v_sprice" id="v_sprice" placeholder="Selling Price">
                                </div>
                            </div>
                        </div>

                        <!-- Discount Type & Value -->
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="discount_type_popup">Discount Type</label>
                                    <select name="discount_type_popup" id="discount_type_popup"
                                        class="form-control js-example-placeholder-single js-states">
                                        <option value="">Select Discount Type</option>
                                        <option value="flat">Flat</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="discount_popup">Discount</label> 
                                    <input type="text" class="form-control" name="discount_popup" id="discount_popup" placeholder="Discount">
                                </div>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-12 mb-3">
                            <label for="v_quantity">Quantity *</label>
                            <input type="text" class="form-control v-quantity" name="v_quantity" id="v_quantity" placeholder="Variant Quantity">
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-3">
                            <button type="button" onclick="submit_form('edit_varient_form')" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<form id="productForm" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-9">
            <div class="card-header mb-3">
                <div class="card-title">
                    <h6>Basic Information</h6>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="product_name" class="form-control @error('name') is-invalid @enderror" value="{{ $product->name }}" required>
                            @if ($errors->has('name'))
                                <div class=" invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span id="productNameError"></span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1" @if($product->is_active == "1") selected @endif>Published</option>
                                <option value="0" @if($product->is_active == "0") selected @endif>Unpublished</option>
                                <option value="2" @if($product->is_active == "2") selected @endif>Draft</option>
                            </select>
                        </div>
                        <span id="productStatusError"></span>
                    </div>
                    <!-- Add Code of Mohit -->
                    <div class="col-md-4  mb-3">
                        <div class="form-group">
                            <label for="weight_type">Country Of Origin <span class="text-danger">*</span></label>
                            <select name="country_origin" class="form-control" required>
                                <option value="select value">Select Country</option>
                                @if (!empty($countries))
                                    @foreach ($countries as $attval)
                                        <option @if ($product->country_origin == $attval->id) selected @endif
                                            value="{{ $attval->id }}">{{ $attval->name }}
                                        </option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                    </div>
                    <!-- End Code of Mohit -->
                    <div class="col-md-12 ">
                        <div class="row">
                            <div class="col  mb-3">
                                <div class="form-group">
                                    <label for="sku">SKU <!--<span class="text-danger">*</span> --></label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" value="{{ $product->sku }}" name="sku"  placeholder="SKU">
                                    <span id="skuError" class="text text-danger"></span>
                                </div>
                            </div>
                            <div class="col  mb-3">
                                <div class="form-group">
                                    <label for="hsn">HSN</label>
                                    <input type="text" class="form-control" id="hsn" name="hsn" placeholder="HSN" value="{{ $product->hsn }}">
                                </div>
                            </div>
                            <div class="col  mb-3">
                                <div class="form-group">
                                    <label for="bar_code">Barcode</label>
                                    <input type="text" class="form-control" id="bar_code" name="bar_code" value="{{ $product->bar_code }}" placeholder="Barcode">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-header mb-3 ">
                <div class="card-title">
                    <h6>Product Details</h6>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="row">
                    @foreach($productDetailSections as $key =>  $detail)
                        <div class="col-md-6  mb-3">
                            <div class="form-group">
                                     @php
                                        $section = Str::snake(
                                            preg_replace('/[^A-Za-z0-9]+/', ' ', $detail->section_name)
                                        );
                                        $fieldName = \Illuminate\Support\Str::snake(preg_replace('/[^A-Za-z0-9]+/', ' ', $detail->section_name));
                                        $nameAttribute = "content_".$key+1; 
                                    @endphp
                                <label for="{{ $section }}">{{ $detail->section_name }}</label>
                                @if($detail->field_type == 'ckeditor' || $detail->field_type == 'textarea')
                                    <textarea class="form-control @if($detail->field_type == 'ckeditor') ck_content @endif"  name="content[]" id="{{ $section }}" rows="4">@if($product->$nameAttribute)  {{ $product->$nameAttribute }} @else {{ $detail->content }} @endif  </textarea>
                                @else 
                                <input type="text" class="form-control @error($fieldName) is-invalid @enderror" name="content[]" 
                                            id="{{ $fieldName }}" 
                                            value="<?php if($product->{'content_'.$key}){
                                                echo @$product->{'content_'.$key};
                                            } else {
                                                echo $detail->content;    
                                            } ?>" 
                                            />
                                @endif 
                            </div>
                        </div>
                    @endforeach 
                </div>
            </div>


            <div class="accordion" id="productAccordion">
                <div class="accordion-item card custom-card">
                    <h2 class="accordion-header" id="headingShipping">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseShipping" aria-expanded="true" aria-controls="collapseShipping">
                            Shipping Information
                        </button>
                    </h2>
                    <div id="collapseShipping" class="accordion-collapse collapse show"
                        data-bs-parent="#productAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="weight">Weight</label>
                                        <input type="text" class="form-control" name="weight" id="weight"
                                            value="{{ $product->weight }}" />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="weight_type">Weight Type</label>
                                        <select
                                            class="form-control select2-original @error('weight_type') is-invalid @enderror"
                                            name="weight_type" id="weight_type">
                                            <option value="">Select Type</option>
                                            <option value="grm"
                                                {{ $product->weight_type == 'grm' ? 'selected' : '' }}>GRM
                                            </option>
                                            <option value="kg"
                                                {{ $product->weight_type == 'kg' ? 'selected' : '' }}>KG
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Related Products --}}
                <div class="accordion-item card custom-card">
                    <h2 class="accordion-header" id="headingRelated">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseRelated" aria-expanded="false" aria-controls="collapseRelated">
                            Related Products
                        </button>
                    </h2>

                    @php
                        $relatedProductIds = explode(',', $product->related_products ?? '');
                    @endphp


                    <input type="hidden" id="preselected_subcategory"
                        value="{{ $product->related_product_subcategory_id ?? '' }}">
                    <input type="hidden" id="preselected_products" value='@json($relatedProductIds)'>
                    <div id="collapseRelated" class="accordion-collapse collapse" data-bs-parent="#productAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                {{-- Category --}}
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="categorys_id" class="form-label">Category</label>
                                        <select name="categorys_id" id="categorys_id" class="form-control"
                                            onchange="getsubcategory(this.value);">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $product->related_product_categores_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="categorysidError">
                                            {{ $errors->first('categorys_id') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Subcategory --}}
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="subcategory_id" class="form-label">Subcategory</label>
                                        <select name="subcategory_id" id="subcategory_id" class="form-control"
                                            onchange="getproduct(this.value);">
                                            <option value="">Select Subcategory</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Related Products --}}
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="Productid" class="form-label">Related Product</label>
                                        <select name="Product_id[]" id="Productid"
                                            class="form-control product_select2" multiple>
                                            <option value="">Select Product</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Attributes --}}
                <div class="accordion-item card custom-card">
                    <h2 class="accordion-header" id="headingAttributes">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseAttributes" aria-expanded="false"
                            aria-controls="collapseAttributes">
                            Attributes
                        </button>
                    </h2>
                    <div id="collapseAttributes" class="accordion-collapse collapse"
                        data-bs-parent="#productAccordion">
                        <div class="accordion-body">
                            <div class="row" id="attribute-pairs-container"></div>
                            <div class="row">
                                <div class="col-12">
                                    <button id="add-attribute-button" class="btn btn-primary" type="button"
                                        onclick="addAttributePair()">+ Select
                                        Attribute</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Modal for New Attribute --}}
                <div class="modal fade" id="addAttributeModal" tabindex="-1" aria-labelledby="addAttributeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Attribute</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="attributes_container">
                            <div class="mb-3">
                                <label for="attributeName" class="form-label">Attribute Name</label>
                                <select class="form-select attribute-select" id="attributeName" name="attributeName">
                                    <option value="">Select an option</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="attributeValue" class="form-label">Attribute Value</label>
                                <input type="text" class="form-control" id="attributeValue"
                                    placeholder="Enter multiple values separated by comma">
                                <small class="text-muted">Enter multiple values separated by comma (e.g. red, green,
                                    blue)</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="saveAttributeButton" class="btn btn-primary">Save
                                Attribute</button>
                        </div>
                    </div>
                </div>
                </div>
                {{-- 4. Pricing --}}
                <div class="card-header mb-3">
                    <div class="card-title">
                        <h6>Pricing </h6>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-4 mb-3">
                        <label for="buying_price">MRP<span class="text-danger">*</span></label>
                        <input type="number" id="buying_price" name="buying_price" class="form-control" required
                            value="{{ $product->buying_price }}" />
                        <span id="buyingPriceError" class="text text-danger"></span>    
                    </div>

                    <div class="col-4 mb-3">
                        <label for="discount_type">Discount Type</label>
                        <select name="discount_type" id="discount_type" class="form-control">
                            <option value="">Select Discount Type</option>
                            <option {{ $product->discount_type == 'flat' ? 'selected' : '' }} value="flat">Flat
                            </option>
                            <option {{ $product->discount_type == 'percentage' ? 'selected' : '' }}
                                value="percentage">Percentage</option>
                        </select>
                    </div>

                    <div class="col-4 mb-3">
                        <label for="discount">Discount</label>
                        <input type="number" id="discount" name="discount" class="form-control"
                            value="{{ $product->discount }}" />
                            
                    </div>

                    <div class="col-4 mb-3">
                        <label for="selling_price">Selling Price <span class="text-danger">*</span></label>
                        <input type="number" id="selling_price" name="selling_price" class="form-control" required
                            readonly value="{{ $product->selling_price }}" />
                    </div>

                    <div class="col-4 mb-3">
                        <label for="qty">Quantity <span class="text-danger">*</span></label>
                        <input type="number" id="qty" name="qty" class="form-control" required
                            value="{{ $product->qty }}" />
                            <span id="qtyError" class="text text-danger"></span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col mb-3">
                        <label for="maxProduct">Maximum Selling Limit (per user)</label>
                        <input type="number" class="form-control" id="maxProduct" name="max_selling_units"
                            value="{{ $product->max_selling_units }}">
                    </div>
                    <div class="col mb-3">
                        <label for="minProduct">Minimum Stock Limit</label>
                        <input type="number" class="form-control" id="minProduct" name="min_selling_units"
                            value="{{ $product->min_selling_units }}">
                    </div>
                </div>
                
            </div>

            
            
            {{-- Variant Section --}}
            <div class="card mt-3">
                @if($product->product_type==2)
                <div class="card-body" id="variant_group_details test2">
                    {!! $variantReleatedProduct !!}
                    <span id="imageError"> </span>
                </div>
                @else
                <div class="card-body" id="variant_group_details">
                    <div class="card mb-4 shadow-sm variant_group_row" id="simple_product_{{ $product->id }}">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <label class="form-check-label" for="out_of_stock_{{ $product->id }}">
                                Upload Product Image & Video
                            </label>
                        </div>
                        <div class="row align-items-start mb-4">
                            <div class="col-auto m-3">
                                <button type="button" class="btn btn-outline-secondary image_upload_button" data-bs-toggle="modal" data-bs-target="#uploadModal_{{ $product->id }}">
                                    <div class="text-center">
                                        <div class="fs-2 fw-bold">+</div>
                                        <div class="small">Add Images & Video</div>
                                    </div>
                                </button>
                            </div>
                           <div class="col">
                                {{-- Image Previews --}}
                                <div class="image-thumbnails-1 d-flex flex-wrap gap-2 mb-2 mt-2">
                                    @if(!empty($images))
                                        @foreach($images as $k=>$image)
                                        @php
                                            $frontCheck = !empty($image['is_front'])? "checked":"";
                                            $backCheck = !empty($image['is_back'])?"checked":"";
                                            $variantIconCheck = !empty($image['is_variant_icon'])?"checked":"";
                                        @endphp
                                            <div class="image-preview-container position-relative" style="width: 100px; height: 170px;">
                                                <img src="{{ $imagePath . $image->graphic }}" alt="Product Image" class="rounded border w-100 " style="object-fit: cover;height:100px"> 
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 delete-image" data-id="{{ $image->id }}" style="width: 22px; height: 22px; line-height: 1;">×</button>
                                                <div class="form-check form-switch d-flex align-items-center justify-content-center mb-0 px-0">
                                                    <input class="form-check-input updateFrontBackIcon" type="radio" name="front_image[{{ $image['product_id'] ?? 0 }}]" data-vid="{{ $image['product_id'] ?? 0 }}" data-id="{{ $image['id'] }}" data-type="front" data-productId="{{ $image['product_id'] }}" value="{{ $image['product_id'] ?? 0 }}-{{ $image['id'] }}" {{ $frontCheck }} id="frontSwitch_$image['product_id']_{{ $image['id'] }}">
                                                    <label class="form-check-label small" for="frontSwitch_{{ $image['product_id'] ?? 0 }}_{{ $image['id'] }}">
                                                        Front Image
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch d-flex align-items-center justify-content-center px-0">
                                                    <input class="form-check-input updateFrontBackIcon" type="radio" name="back_image[{{ $image['product_id'] ?? 0 }}]" data-vid="{{ $image['product_id'] ?? 0 }}" data-id="{{ $image['id'] }}" data-type="back" data-productId="{{ $image['product_id'] }}" value="{{ $image['product_id'] ?? 0 }}-{{ $image['id'] }}" {{ $backCheck }} id="backSwitch_{{ $image['product_id'] ?? 0 }}_{{ $image['id'] }}">
                                                    <label class="form-check-label small" for="backSwitch_{{ $image['product_id'] ?? 0 }}_{{ $image['id'] }}">
                                                        Back Image
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch d-flex align-items-center justify-content-center px-0">
                                                    <input class="form-check-input updateFrontBackIcon" type="radio" name="variant_icon[{{ $image['product_id'] ?? 0 }}]" data-vid="{{ $image['product_id'] ?? 0 }}" data-id="{{ $image['id'] }}" data-type="icon" data-productId="{{ $image['product_id'] }}" value="{{ $image['product_id'] ?? 0 }}-{{ $image['id'] }}" {{ $variantIconCheck }} id="iconSwitch_{{ $image['product_id'] ?? 0 }}_{{ $image['id'] }}">
                                                    <label class="form-check-label small" for="iconSwitch_{{ $image['product_id'] ?? 0 }}_{{ $image['id'] }}">
                                                    Variant Icon
                                                    </label>
                                                </div>    
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="image-thumbnails d-flex flex-wrap gap-2 mb-2 mt-2">
                                </div>

                                {{-- Video Previews --}}
                                <div class="video-thumbnails-1 d-flex flex-wrap gap-2 mt-2">
                                    @if(!empty($videos))
                                        @foreach($videos as $video)
                                            <div class="image-preview-container position-relative" style="width: 100px; height: 100px;">
                                                <video class="rounded border w-100 h-100" style="object-fit: cover;" controls>
                                                    <source src="{{ asset($imagePath . $video->graphic) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 delete-image"
                                                    data-id="{{ $video->id }}"
                                                    style="width: 22px; height: 22px; line-height: 1;">×</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="video-thumbnails d-flex flex-wrap gap-2 mt-2">
                                </div>
                            </div>
                            
                        </div>

                        <!-- Upload Modal -->
                        <div class="modal fade" id="uploadModal_{{ $product->id}}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content p-3">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Upload Files for Group</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Select Images</label>
                                            <input type="file" 
                                                name="variant_images[{{ $product->id }}][]" 
                                                accept="image/*" 
                                                multiple
                                                onchange="previewImagesSimple(event, {{ $product->id }})" 
                                                class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Select Video</label>
                                            <input type="file" 
                                                name="variant_video[{{ $product->id }}]" 
                                                accept="video/*"
                                                onchange="previewVideoSimple(event, {{ $product->id }})" 
                                                class="form-control">
                                        </div>
                                        <hr/>
                                        <h6 class="fw-bold mb-2">Image Preview</h6>
                                        <div id="preview_images_{{ $product->id }}" class="d-flex flex-wrap gap-2"></div>

                                        <h6 class="fw-bold mt-4 mb-2">Video Preview</h6>
                                        <div id="preview_video_{{ $product->id }}" class="d-flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
                @endif
                <div class="row mt-3">
                    <h3><u>Seo Feature</u></h3>
                    <div class="col">
                        <div class="form-group">
                            <label for="meta_title">Meta Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="meta_title" id="meta_title" value="{{$product->meta_title}}" />
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" class="form-control" name="meta_keywords" id="meta_keywords" value="{{$product->meta_keywords}}"  />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="meta_description">Meta Description </label>
                            <textarea class="form-control" name="meta_description" id="meta_description" cols="30" rows="3">{{$product->meta_description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="seo_content">Web SEO Content </label>
                            <textarea class="form-control" name="seo_content" id="seo_content" cols="30" rows="3">{{$product->seo_content }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="mb-3 text-first btn_add mt-3">
                <?php
                if(@$product->product_type==1){
                    $previousStep = 'step1';
                } else {
                    $previousStep = 'step2';
                }
                ?>
                <button type="button" class="btn btn-primary prevBtn btn-lg" onclick="onclickPrevious('step1')">Previous</button>
                <button type="button" id="finish" class="btn btn-primary nextBtn btn-lg">Save</button>
                <button type="button" id="saveAsdraf" class="btn btn-danger nextBtn btn-lg">Save as Draf</button>
            </div>
        </div>

        <div class="col-md-3 mb-3 mt-3">
            <div class="card-header">
                <div class="card-title">
                    <h6>Settings</h6>
                </div>
            </div>
            <hr>

            <div class="card-body">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_new"
                        name="is_new_arrivals" @if (isset($product) && $product->is_new_arrivals == 1) checked @endif />
                    <label class="form-check-label" for="is_new">New Arrivals</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="status"
                        name="best_seller" @if (isset($product) && $product->best_seller == 1) checked @endif />
                    <label class="form-check-label" for="status">Best Sellers</label>
                </div>
            </div>


            <div class="card-header mb-3 mt-3">
                <div class="card-title">
                    <h6>Categories</h6>
                </div>
            </div>
            <hr>
            @php
                $selectedCategories = json_decode($product->category_id, true);
                $selectedCategories = is_array($selectedCategories) ? $selectedCategories : [];

                $selectedSubCategories = json_decode($product->sub_category_id, true);
                $selectedSubCategories = is_array($selectedSubCategories) ? $selectedSubCategories : [];

                $selectedChildCategories = json_decode($product->child_category_id, true);
                $selectedChildCategories = is_array($selectedChildCategories) ? $selectedChildCategories : [];
            @endphp

            <div class="card-body">

                @foreach ($categories as $category)
                    <div class="form-check">
                        <input
                            class="form-check-input main-cat-checkbox"
                            type="checkbox"
                            id="main_cat_{{ $category->id }}"
                            name="category_id[]"
                            value="{{ $category->id }}"
                            data-product-detail-managers="{{ $category->product_detail_manager ?? '' }}"
                            @checked(
                                $product->main_category_id == $category->id ||
                                in_array($category->id, $selectedCategories)
                            )
                            onchange="toggleSubCategories({{ $category->id }})"
                        >

                        <label
                            class="form-check-label"
                            for="main_cat_{{ $category->id }}"
                        >
                            {{ $category->name }}
                        </label>
                    </div>
                    @if ($category->children->count())

                        <div
                            id="subcategories_{{ $category->id }}"
                            class="ms-4"
                        >

                            @foreach ($category->children as $sub)

                                <div class="form-check">

                                    <input
                                        class="form-check-input sub-cat-checkbox"
                                        type="checkbox"
                                        id="sub_cat_{{ $sub->id }}"
                                        name="sub_category_id[]"
                                        value="{{ $sub->id }}"
                                        data-product-detail-managers="{{ $sub->product_detail_manager ?? '' }}"
                                        @checked(
                                            $product->main_sub_category_id == $sub->id ||
                                            in_array($sub->id, $selectedSubCategories)
                                        )
                                        onchange="toggleChildCategories({{ $sub->id }})"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="sub_cat_{{ $sub->id }}"
                                    >
                                        {{ $sub->name }}
                                    </label>
                                </div>
                                @if ($sub->children->count())
                                    <div
                                        id="childcategories_{{ $sub->id }}"
                                        class="ms-5"
                                    >
                                        @foreach ($sub->children as $child)
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input child-cat-checkbox"
                                                    type="checkbox"
                                                    id="child_cat_{{ $child->id }}"
                                                    name="child_category_id[]"
                                                    value="{{ $child->id }}"
                                                    data-product-detail-managers="{{ $child->product_detail_manager ?? '' }}"
                                                    @checked(
                                                        $product->main_child_category_id == $child->id ||
                                                        in_array($child->id, $selectedChildCategories)
                                                    )
                                                >
                                                <label
                                                    class="form-check-label"
                                                    for="child_cat_{{ $child->id }}"
                                                >
                                                    {{ $child->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <hr>
                @endforeach
            </div>

            <div class="card-header mb-3 mt-3">
                <div class="card-title">
                    <h6>Collections</h6>
                </div>
            </div>
            <hr> 
            @php 
                $selectedCollections = json_decode($product->collection_ids,true); 
                $selectedCollections = is_array($selectedCollections) ? $selectedCollections : []; 
            @endphp
            <div class="card-body">
                @foreach($collections as $col)
                    <div class="form-check">
                        <input
                            class="form-check-input main-cat-checkbox"
                            type="checkbox"
                            id="collection_id"
                            name="collection_ids[]"
                            value="{{ $col->id }}"
                            @checked(
                                $product->main_collection_id == $col->id ||
                                in_array($col->id, $selectedCollections)
                            )>
                        <label class="form-check-label"for="main_collection_id">{{ $col->name }}</label>
                    </div>
                @endforeach 
            </div>


            @php
                $selectedTags = explode(',', $product->product_tags ?? '');
            @endphp
        </div>
    </div>
</form>


<div class="modal fade" id="addVariantGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Variant Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addVariantGroupForm">
                    <input type="hidden" name="base_variant_id" id="base_variant_id" value="">
                    <div class="mb-3">
                        <label class="form-label">Select Variant Value</label>
                        <select name="variant_value_id" id="variant_value_id" class="form-select">
                            <option value="">Select</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="addSelectedVariantGroup()">Add</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    window.CKEDITOR_BASEPATH = "{{ asset('assets/js/ckeditor/') }}/";
</script>
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('seo_content');
    CKEDITOR.replace('meta_description');
    CKEDITOR.replace('short_description'); 
</script>
@foreach($productDetailSections as $datas)
     @php
        $section = Str::snake(
            preg_replace('/[^A-Za-z0-9]+/', ' ', $datas->section_name)
        );
    @endphp
    @if($datas->field_type == 'ckeditor')
        <script>
            CKEDITOR.replace("{{ $section }}")
        </script>
    @endif 
@endforeach
<script>
    var getAttributesValues = "{{ route('admin-product-attribute-values') }}";
    var getSubCategory = "{{ route('admin-product-ajax-subcategory') }}";
    var getProduct = "{{ route('admin-product-ajax-getproduct') }}";
    var productLimit = "{{ url('admin/product/update-product-variant-limit/') }}";
    window.attributes = @json($attributesData);
    window.preselectedAttributes = @json($preselectedAttributes);
</script>

<script src="{{ asset('assets/js/product/add-product.js') }}"></script>
<script>

    $(document).off('click', '.delete-image').on('click', '.delete-image', function () { 
        const imageId   = $(this).data('id'); 
        const container = $(this).closest('.image-preview-container');

        if (!confirm("Are you sure you want to delete this image?")) return;

        $.ajax({
            url: "{{ route('admin-product-fileDelete') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: imageId
            },
            success: function (response) {
                if (response.success) {
                    container.remove();
                } else {
                    alert(response.message || 'Failed to delete image.');
                }
            },
            error: function () {
                alert('Server error. Please try again.');
            }
        });
    });

    $(document).on('change', '.updateFrontBackIcon', function () {
        let vid = $(this).data('vid');
        let id = $(this).data('id');
        let type = $(this).data('type');
        let productid = $(this).data('productid');
        if(vid && id && type) {
            $.ajax({
                url:"{{ route('admin-product-updateFrontBackIcon') }}",
                data:{vid:vid,id:id,type:type,productid},
                dataType:"json",
                method:"post",
                success:function(resp){
                    console.log(resp);
                },
                error:function(){

                }
            });
        }
    });

    $(document).ready(function() {
        $('#productForm').validate({
            errorClass: 'is-invalid',
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group, .mb-3').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });

        $('.nextBtn').on('click', function(e) {
            var nextBtnId = $(this).attr('id');
            const $btn = $('.nextBtn');
            const originalHtml = $btn.html();
            e.preventDefault();

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            const form = $('#productForm');

            // if (form.valid()) {
            const formElement = $('#productForm')[0];
            var globalSku = $('#sku').val();
            var globalBuyingPrice = $('#buying_price').val(); 
            var globalQty = $('#qty').val(); 
            var globalProductName = $('#product_name').val(); 

            var globalProductStatus = $('#status').val(); 

            if(globalProductName == '' || globalProductName == null || globalProductName.length < 3 || globalProductName.length > 254){
                $('#productNameError').text("Please Enter Product Name");
                return false;  
            }

            if(globalProductStatus == '' || globalProductStatus == null){
                $('#productStatusError').text('Please Select any status'); 
                return false; 
            }

            if(globalSku == '' || globalSku == null ){
                $('#skuError').text("Please Enter Product Sku"); 
                return false; 
            } 

            if(globalBuyingPrice == '' || globalBuyingPrice == null || globalBuyingPrice == 0){
                $('#buyingPriceError').text("Please Enter Product MRP"); 
                  return false; 
            }

            if(globalQty == '' || globalQty == null || globalQty == 0){ 
                $('#qtyError').text("Please Enter Product Quantity"); 
                  return false; 
            }
            document.querySelectorAll('#productForm input[type="file"][name^="variant_images["]').forEach(input => {
                input.removeAttribute('name');
            });
            const formData = new FormData(formElement);
            $('.updateFrontBackIcon:checked').each(function () {

                const variantId = $(this).data('vid');
                const type = $(this).data('type');
                const graphicId = $(this).data('id');

                if (!variantId || !graphicId || !type) {
                    return;
                }

                if (type === 'front') {
                    formData.append(`existing_front_image[${variantId}]`,graphicId);

                } else if (type === 'back') {
                    formData.append(`existing_back_image[${variantId}]`,graphicId);

                } else if (type === 'icon') {
                    formData.append(`existing_variant_icon[${variantId}]`,graphicId);
                }
            });
            Object.entries(window.uploadedImages).forEach(([variantId, images]) => {
                console.log('VARIANT:', variantId);
                images.forEach((imageData) => {
                    formData.append(
                        `variant_images[${variantId}][]`,
                        imageData.file
                    );
                    formData.append(
                        `image_id[${variantId}][]`,
                        imageData.imageId
                    );

                    if (imageData.front) {
                        formData.append(
                            `front_image[${variantId}]`,
                            imageData.imageId
                        );
                    }

                    if (imageData.back) {
                        formData.append(
                            `back_image[${variantId}]`,
                            imageData.imageId
                        );
                    }

                    if (imageData.icon) {
                        formData.append(
                            `variant_icon[${variantId}]`,
                            imageData.imageId
                        );
                    }
                });
            }); 
            
            if(nextBtnId == 'saveAsdraf'){ 
                swal.fire({
                    title: "Are you sure?",
                    text: "Want to save this product as draf?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes,",
                    cancelButtonText: "No, cancel",
                    reverseButtons: true
                }).then(function(result){
                    if(result.isConfirmed){
                        formData.append("save_as_draf", 1);
                        submitProductDetail(formData,nextBtnId,$btn); 
                    }
                }); 
            }
            else{
                let variantErrors = [];
                let variantIds = new Set();
                $('.out-of-stock-toggle').each(function(){
                    const variantId = $(this).data('variant-id');
                        if (variantId) {
                            variantIds.add(String(variantId));
                        }
                }); 
                $('.updateFrontBackIcon:checked').each(function () {
                    const variantId = $(this).data('vid');

                    if (variantId) {
                        variantIds.add(String(variantId));
                    }
                });
                variantIds.forEach(function (variantId) {
                    let hasImage = false;
                    let hasFront = false;
                    let hasBack = false; 
                    const images = window.uploadedImages?.[variantId] || [];
                    if (images.length > 0) {
                        hasImage = true;
                        images.forEach(function (imageData) {
                            if (imageData.front) {
                                hasFront = true;
                            }
                            if (imageData.back) {
                                hasBack = true;
                            }
                        });
                    }
                    $('.updateFrontBackIcon:checked').each(function () {

                        const existingVariantId = String($(this).data('vid'));
                        const type = $(this).data('type');
                        const graphicId = $(this).data('id');

                        if (existingVariantId === String(variantId) &&
                            graphicId) {
                            hasImage = true;
                            if (type === 'front') {
                                hasFront = true;
                            }
                            if (type === 'back') {
                                hasBack = true;
                            }
                        }
                    });
                    if (!hasImage) {
                        variantErrors.push(
                            `Variant ${variantId}: Please add an image.`
                        );

                    } else if (!hasFront) {
                        variantErrors.push(
                            `Variant ${variantId}: Please select a front image.`
                        );
                    } else if (!hasBack) {
                        variantErrors.push(
                            `Variant ${variantId}: Please select a back image.`
                        );
                    }
                }); 
                if (variantErrors.length > 0) {
                    Swal.fire({
                        title: "Image Required",
                        html: variantErrors.join('<br>'),
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return false;
                }
                submitProductDetail(formData,nextBtnId,$btn); 
            }    
        });
    });


    $(document).on('blur', 'input[type="number"]', function() {
        let val = parseFloat($(this).val());
        if (val < 0 || isNaN(val)) {
            $(this).val(0);
        }
    });

    $(document).on('keydown', 'input[type="number"]', function(e) {
        // Block "-" key
        if (e.key === '-' || e.keyCode === 189) {
            e.preventDefault();
        }
    });

    function submitProductDetail(formData,nextBtnId,$btn){
        $.ajax({
            url: "{{ route('admin-product-save.step3') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
            },
            beforeSend: function() {
                if(nextBtnId == 'finish'){
                    $btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...`);
                }else{
                    $btn.prop('disabled',true); 
                }
            },
            success: function(response) {
                if(!response.success){
                    Swal.fire({ icon: 'error', title: 'Validation Error', text: response.message });
                    return false;
                } 
                else 
                {
                    if(nextBtnId =='finish' || nextBtnId =='saveAsdraf'){
                        window.location.href = "{{ route('admin-product-list') }}";
                        return false;
                    }
                    $('#tab2').html("");
                    $('#formTabs .nav-link').removeClass('active');
                    $('#formTabs .nav-link[data-tab="tab4"]').addClass('active');
                    $('#tab2').html(response.seoView);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) 
                {
                    const errors = xhr.responseJSON.errors;
                    $('#formErrorList').empty();
                    $('#formErrorPopup').addClass('d-none');
                    $('.invalid-feedback').remove();
                    $('.is-invalid').removeClass('is-invalid');
                    $.each(errors, function(key, messages) {
                        messages.forEach(msg => {
                            $('#formErrorList').append('<li>' + msg + '</li>');
                        });
                        let field = $('[name="' + key + '"]');
                        if (!field.length && key.includes('.')) {
                            const [base, index] = key.split('.');
                            field = $('[name="' + base + '[' + index +']"]');
                        }
                        if (field.length) {
                            field.addClass('is-invalid');
                            field.after('<div class="invalid-feedback d-block">' +messages[0] + '</div>');
                        }
                    });
                    $('#formErrorPopup').removeClass('d-none');
                    $btn.prop('disabled', false).html(originalHtml);
                } 
                else 
                {
                    alert('Something went wrong. Please try again.');
                    $btn.prop('disabled', false).html(originalHtml);
                }
            },                
            complete: function() {
                if(nextBtnId !='finish'){
                    $btn.prop('disabled', false).html(originalHtml);
                }
            }
        });
    }

    function onclickPrevious(value) {
        const $btn = $('.prevBtn');
        const originalHtml = $btn.html();
        const formData = new FormData();
        formData.append('product_id', $('#product_id').val());
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('step', "step2");

        $.ajax({
            url: "{{ route('admin-product-previousStep') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $btn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Processing...
            `);
            },
            success: res => {
                if (res.success) {
                    $('#formTabs .nav-link').removeClass('active');
                    $('#formTabs .nav-link[data-tab="tab2"]').addClass('active');
                    $('#tab2').html("").html(res.mainView);
                } else {
                    alert(res.message || 'Something went wrong.');
                }


            },
            error: xhr => {
                const msg = xhr.status === 422 ?
                    Object.values(xhr.responseJSON.errors).map(e => e[0]).join('\n') :
                    'Server error';
                alert(msg);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }
</script>
