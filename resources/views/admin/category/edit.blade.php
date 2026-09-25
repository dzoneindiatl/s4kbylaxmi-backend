@extends('admin.layout.master')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endpush
@section('content')
@include('admin.layout.response_message')

@php
    $pageTitle = match(request()->query('type')) {
        'sub-category' => 'Sub Categories',
        'child-category' => 'Child Categories',
        default => 'Categories',
    };

    $query = http_build_query([
        'type' => request()->query('type'),
        'endesid' => request()->query('endesid'),
    ]);
@endphp
<!-- Page Header -->
<style>
    .category-image-wrapper {
        position: relative;
        display: inline-block;
    }

    .delete-category-image {
        position: absolute;
        top: 2px;
        right: 2px;
        background: red;
        color: #fff;
        font-size: 12px;
        padding: 2px 6px;
        cursor: pointer;
        border-radius: 50%;
    }

    .category-thumb-image-wrapper {
        position: relative;
        display: inline-block;
    }

    .delete-category-thumb-image {
        position: absolute;
        top: 2px;
        right: 2px;
        background: red;
        color: #fff;
        font-size: 12px;
        padding: 2px 6px;
        cursor: pointer;
        border-radius: 50%;
    }
</style> 
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <a href="{{ route('admin-category.index') }}?{{ $query }}" class="btn btn-dark">
        Back
    </a>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit {{$pageTitle}}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header Close -->

@error('error')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">Close</button>
    </div>
@enderror

<div class="row">
    <div class="col-xl-12">
        <form action="{{ route('admin-' . $model . '.update', base64_encode($category->id)) }}" method="post" id="categoryForm" enctype="multipart/form-data">
            @csrf
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Edit {{$pageTitle}}
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($pageTitle) && $pageTitle == 'Categories')
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card-body p-0">
                                <div class="mb-3">
                                    <label for="select_category_type" class="form-label"><span
                                            class="text-danger">*</span>Select Category Type</label>
                                    <select
                                        class="select2-original form-control @error('select_category_type') is-invalid @enderror"
                                        name="select_category_type" id="select_category_type">
                                        <option value="">Select Type</option>
                                        <option value="1" {{ $category->category_type_id == 1 ? 'selected' : '' }}>
                                            Collection</option>
                                        <option value="2" {{ $category->category_type_id == 2 ? 'selected' : '' }}>
                                            Category</option>
                                    </select>
                                    @if ($errors->has('select_category_type'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('select_category_type') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                
                    @if(isset($pageTitle) && $pageTitle != 'Categories')
                        <input type="hidden" name="parent_id" value="{{ request()->query('endesid') }}">
                        <input type="hidden" name="type" value="{{ request()->query('type') }}">
                    @endif
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card-body p-0">

                                <div class="mb-3">
                                    <label for="name" class="form-label"><span class="text-danger">*
                                        </span>Name</label>
                                    <input type="text" class="form-control" id="edit_name" name="name"
                                        placeholder="Enter Name" onkeyup="editDisplaySlug($(this))"
                                        value="{{ $category->name }}">
                                    {{-- <h6 class="edit-category-slug mt-2"></h6> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label for="category" class="form-label">Url</label>
                            <input type="url" class="form-control"name="url" value="{{ $category->url }}">
                        </div>
                        <div class="col-xl-6 mb-3 oldSlug">
                            <label for="category" class="form-label"><span class="text-danger">*
                                </span>Slug</label>
                            <input type="text" class="form-control edit-category" disabled
                                value="{{ $category->slug }}">
                        </div>
                        <div class="col-xl-6 mb-3 newSlug" style="display: none">
                            <label for="category" class="form-label"><span class="text-danger">*
                                </span>Slug</label>
                            <input type="text" class="form-control edit-category-slug" disabled value="">
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label for="image" class="form-label"><span class="text-danger">
                                </span>Banner Image (<span class="text-danger small">
                                    Note: Image size should be exactly <strong>1060 × 1600</strong> pixels.
                                </span> )
                            </label>
                            
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image" accept="image/*">
                            @if (!empty($category->getAttributes()['image']))
                                <div class="category-image-wrapper" data-id="{{ $category->id }}">
                                    <a href="{{ env('WEBSITE_URL') . '/uploads/categories/' . $category->getAttributes()['image'] }}" target="_blank">
                                        <img src="{{  env('WEBSITE_URL')  .'/uploads/categories/' . $category->getAttributes()['image'] }}"
                                            width="100" height="100" alt="Category Image">
                                    </a>

                                    <span class="delete-category-image" title="Delete Image">✖</span>
                                </div>
                            @endif
                            @if ($errors->has('image'))
                            <div class="invalid-feedback">
                                {{ $errors->first('image') }}
                            </div>
                            @endif
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label for="thumbnail_image" class="form-label"><span class="text-danger">
                                </span>Thumbnail Image (<span class="text-danger small">
                                    Note: Image size should be exactly <strong>1060 × 1600</strong> pixels.
                                </span>)
                            </label>
                            <input type="file" class="form-control @error('thumbnail_image') is-invalid @enderror"
                                id="thumbnail_image" name="thumbnail_image" accept="image/*"> 
                            @if (!empty($category->getAttributes()['thumbnail_image']))
                            <div class="category-thumb-image-wrapper" data-id="{{ $category->id }}">
                                    <a href="{{ env('WEBSITE_URL') . '/uploads/category/' . $category->getAttributes()['thumbnail_image'] }}" target="_blank">
                                        <img src="{{ env('WEBSITE_URL') . '/uploads/category/' . $category->getAttributes()['thumbnail_image'] }}"
                                            width="100" height="100" alt="Category Thumbnail Image">
                                    </a>

                                    <span class="delete-category-thumb-image" title="Delete Image">✖</span>
                                </div>    
                            @endif
                            @if ($errors->has('thumbnail_image'))
                            <div class="invalid-feedback">
                                {{ $errors->first('thumbnail_image') }}
                            </div>
                            @endif
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label for="video" class="form-label"><span class="text-danger"> </span>Video</label>
                            <input type="file" class="form-control @error('video') is-invalid @enderror" id="video"
                                name="video" accept="video/*">
                            @if (!empty($category->video))
                            <video height="70" controls>
                                <source src="{{ isset($category->video) ? $category->video : '' }}" type="video/mp4">
                            </video>
                            @endif
                            @if ($errors->has('video'))
                            <div class="invalid-feedback">
                                {{ $errors->first('video') }}
                            </div>
                            @endif
                        </div>
                        <div class="col-xl-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('title') is-invalid @enderror" name="description"
                                id="description" cols="30"
                                rows="5">{!! isset($category->description) ? $category->description : old('description') !!}</textarea>
                            @if ($errors->has('description'))
                            <div class=" invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

         @if(isset($pageTitle) && $pageTitle == 'Categories')
            <div class="accordion mt-4" id="categoryAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingProductDetailManager">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseProductDetailManager" aria-expanded="false"
                            aria-controls="collapseProductDetailManager">
                            <div class="card-header">
                                <div class="card-title">
                                    Product Detail Manager
                                </div>
                            </div>
                        </button>
                        
                    </h2>
                    <div id="collapseProductDetailManager" class="accordion-collapse collapse" aria-labelledby="headingProductDetailManager"
                        data-bs-parent="#categoryAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-xl-12 select2-error">
                                    <label for="productDetailManagerSelect" class="form-label">Product Detail Section</label>
                                    <?php if($category->product_detail_manager){
                                        $assignedManagers = explode(",",$category->product_detail_manager);
                                        $managerNames = \App\Models\ProductDetailManager::whereIn('id', $assignedManagers)->pluck('section_name')->toArray();
                                        echo '<span style="color:green;font-size:14px;font-weight:bold;"> &nbsp; &nbsp; ( Assigned: ' . implode(", ", $managerNames) . ' ) </span>';
                                    } else { ?>
                                        <span style="color:red;font-size:14px;font-weight:bold;float:left"> &nbsp; &nbsp; <?php if($productDetailManagers->isEmpty()){  
                                            echo "( Please add product detail manager atleast one ) <a href='". route('admin-product-detail-managers.create') ."' class='btn' target='_blank'>Add Product Detail Manager</a>"; } ?>
                                        </span>
                                    <?php } ?>
                                    @php
                                        $selectedProductDetailManagers = old('productDetailManager', []);
                                        if (empty($selectedProductDetailManagers) && !empty($category->product_detail_manager)) {
                                            $selectedProductDetailManagers = array_filter(explode(',', $category->product_detail_manager));
                                        }
                                    @endphp
                                    <select class="js-example-placeholder-single js-states form-control"
                                        multiple="multiple" name="productDetailManager[]" id="productDetailManagerSelect">
                                        @forelse ($productDetailManagers as $productDetailManager)
                                        <option value="{{ $productDetailManager->id }}"
                                            {{ in_array($productDetailManager->id, $selectedProductDetailManagers) ? 'selected' : '' }}>
                                            {{ $productDetailManager->section_name ?? "N/A" }}
                                        </option>
                                        @empty
                                        <option value="" selected>No Data found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Manage Size Chart
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <select name="size_chart_id" class="form-control" id="">
                                <option value="">Select</option>
                                @foreach($sizeCharts as $chart)
                                    <option value="{{ $chart->id }}" {{ $category->size_chart_id == $chart->id ? 'selected' : ''  }}>{{ ucwords(str_replace('_',' ',$chart->title)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Variants  <span style="color:red;font-size:14px;font-weight:bold;float:right"> &nbsp; &nbsp; <?php if($variants->isEmpty()){  echo "( Please add varinat atleast one ) <a href='". route('admin-variants.store') .  "' class='btn' target='_blank'>Add Variant</a>"; } ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 select2-error">
                            <label for="category_id" class="form-label"><span class="text-danger">
                                </span>Variants</label>
                            <select class="js-example-placeholder-single js-states form-control" multiple="multiple"
                                name="variantsData[]" id="variantsSelect">
                               
                                @forelse ($variants as $variant)
                                <option value="{{ $variant->id }}"
                                    {{ in_array($variant->id, $categoryVariants) ? 'selected' : '' }}>
                                    {{ $variant->name }}</option>
                                @empty
                                <option value="" selected>No Data found</option>
                                @endforelse
                            </select>

                        </div>
                    </div>
                </div>
            </div>


            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Attributes
                        <span style="color:red;font-size:14px;font-weight:bold;float:right"> &nbsp; &nbsp; <?php if($attributes->isEmpty()){  echo "( Please add attribute atleast one ) <a href='". route('admin-attributes.create') ."' class='btn' target='_blank'>Add Attribute</a>"; } ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 select2-error">
                            <label for="category_id" class="form-label">
                                Attributes <span class="text-danger">*</span>
                            </label>
                            <select class="js-example-placeholder-single js-states form-control" multiple="multiple"
                                name="attributesData[]" id="attributesSelect">
                                <!-- <option value="" selected>None</option> -->
                                @forelse ($attributes as $attribute)
                                <option value="{{ $attribute->id }}"
                                    {{ in_array($attribute->id, $categoryAttribute) ? 'selected' : '' }}>
                                    {{ $attribute->name }}</option>
                                @empty
                                <option value="" selected>No Data found</option>
                                @endforelse
                            </select>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Taxes
                        <span style="color:red;font-size:14px;font-weight:bold;float:right"> &nbsp; &nbsp; <?php if($taxes->isEmpty()){  echo "( Please add tax atleast one, if  you have any inclusive product in this category) <a href='". route('admin-taxes.create') . "' class='btn' target='_blank'>Add Taxes</a>"; } ?></span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 mb-3">
                            <label class="form-label">
                                Tax Option <span class="text-danger">*</span>
                            </label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('tax_option') is-invalid @enderror"
                                        type="radio"
                                        name="tax_option"
                                        id="includeTax"
                                        value="inclusive"
                                        {{ old('tax_option', $categoryTaxesValues['tax_option'] ?? '') === 'inclusive' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="includeTax">Inclusive Tax</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('tax_option') is-invalid @enderror"
                                        type="radio"
                                        name="tax_option"
                                        id="excludeTax"
                                        value="exclusive"
                                        {{ old('tax_option', $categoryTaxesValues['tax_option'] ?? '') === 'exclusive' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="excludeTax">Exclusive Tax</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 mb-3">
                            <label class="form-label">
                                Tax Type <span class="text-danger">*</span>
                            </label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('tax_type') is-invalid @enderror"
                                        type="radio"
                                        name="tax_type"
                                        id="flat"
                                        value="flat"
                                        onclick="changeTaxType('flat')"
                                        {{ old('tax_type', $categoryTaxesValues['tax_type'] ?? '') === 'flat' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flat">Flat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('tax_type') is-invalid @enderror"
                                        type="radio"
                                        name="tax_type"
                                        id="floating"
                                        value="floating"
                                        onclick="changeTaxType('floating')"
                                        {{ old('tax_type', $categoryTaxesValues['tax_type'] ?? '') === 'floating' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="floating">Floating</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 select2-error">
                            <label for="tax_rate" class="form-label">Taxes</label>
                            <select class="js-example-placeholder-single js-states form-control"
                                multiple="multiple" name="tax_rate[]" id="tax_rate">
                                <option value="0" disabled>Select Tax Rate</option>
                                @forelse ($taxes as $tax)
                                <option value="{{ $tax->id }}"
                                    {{ in_array($tax->id, $categoryTaxes) ? 'selected' : '' }}>
                                    {{ $tax->tax_rate }}
                                </option>
                                @empty
                                <option value="" selected>No Data found</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Footer SEO data
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 mb-3">
                            <label for="seo_data" class="form-label">Seo Data</label>
                            <textarea class="form-control" name="seo_data" id="seo_data" cols="30"
                                rows="5">{{ $category->value }}</textarea>
                        </div>

                        <div class="col-xl-6 mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                placeholder="Meta TItle" value="{{ $category->meta_title }}">
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                placeholder="Meta Keywords" value="{{ $category->meta_keywords }}">
                        </div>
                        <div class="col-xl-12 mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" name="meta_description" id="meta_description" cols="30"
                                rows="5">{{ $category->meta_description }}</textarea>
                        </div>


                    </div>
                </div>

            </div>
            @endif 
            <div class="px-4 py-3 border-top border-block-start-dashed d-sm-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/plugin/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/form-validation.js') }}"></script>
<script src="{{ asset('assets/js/custom/category.js') }}"></script>
<script>

var getCategoryTaxRateListRoute = "{{ route('admin-category.getTaxRateList') }}";
var taxOption = $("input[name='tax_option']:checked").val();
$("input[name='tax_option']").on('change', function() {
    taxOption = $("input[name='tax_option']:checked").val();
    taxType = $("input[name='tax_type']:checked").val();
    changeTaxType(taxType)
});
$(function() {
    // Tax field toggle
    $('#taxesSelect').on('change', function() {
        const selectedTaxes = $(this).val() || [];
        $('.taxContainers').hide().find('input').prop('disabled', true);
        selectedTaxes.forEach(id => $(`.taxDiv${id}`).show().find('input').prop('disabled', false));
    });

    ['description', 'seo_data'].forEach(id => {
    
      
        CKEDITOR.replace(id, {
            filebrowserUploadUrl: '<?php echo URL()->to('base/uploder'); ?>',
            enterMode: CKEDITOR.ENTER_BR
        });
        CKEDITOR.config.allowedContent = true;
    });
});

$(document).on('click', '.delete-category-image', function () {
    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }

    let wrapper = $(this).closest('.category-image-wrapper');
    let categoryId = wrapper.data('id');

    $.ajax({
        url: "{{ route('admin-category.image.delete') }}",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            category_id: categoryId,
            type: 'image',
        },
        success: function (response) {
            if (response.success) {
                wrapper.remove();

            } else {
                alert(response.message);
                setTimeout(function () {
                    location.reload();
                }, 500);
            }
        },
        error: function () {
            alert('Something went wrong.');
        }
    });
});

$(document).on('click', '.delete-category-thumb-image', function () {
    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }

    let wrapper = $(this).closest('.category-thumb-image-wrapper');
    let categoryId = wrapper.data('id');

    $.ajax({
        url: "{{ route('admin-category.image.delete') }}",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            category_id: categoryId,
            type: 'thumb-image'
        },
        success: function (response) {
            if (response.success) {
                wrapper.remove();

            } else {
                alert(response.message);
                setTimeout(function () {
                    location.reload();
                }, 500);
            }
        },
        error: function () {
            alert('Something went wrong.');
        }
    });
});
</script>
@endpush