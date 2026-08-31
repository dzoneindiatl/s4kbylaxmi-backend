@extends('admin.layout.master')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/assets/libs/dropzone/dropzone.css') }}">
<link href="{{ asset('public/assets/plugin/tagify/tagify.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('public/assets/js/ckeditor/ckeditor.js') }}"></script>

@endpush

@section('content')
<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <a class="btn btn-dark" href="{{ url()->previous() }}">Back</a>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($action) }} Product Detail Section</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header Close -->
<div class="row">
    <div class="col-xl-12">
        <form action="" method="post" enctype="multipart/form-data" id="createBlogForm">
            @csrf
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Create Product Detail Section
                    </div>
                </div>
                <div class="card-body add-products p-0">
                    <div class="p-4">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12">
                                <div class="card custom-card shadow-none mb-0 border-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">

                                            <div class="col-xl-6">
                                                <label for="section_name" class="form-label"><span class="text-danger">*
                                                    </span>Section Name</label>
                                                <input type="text"
                                                    class="form-control @error('section_name') is-invalid @enderror" id="section_name"
                                                    name="section_name"
                                                    value="{{isset($productDetailManager->section_name) ? $productDetailManager->section_name: old('section_name')}}"
                                                    placeholder="Section Name">
                                                @if ($errors->has('section_name'))
                                                <div class=" invalid-feedback">
                                                    {{ $errors->first('section_name') }}
                                                </div>
                                                @endif
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="field_type" class="form-label">Field Type</label>
                                                <select name="field_type" id="field_type" class="form-control @error('field_type') is-invalid @enderror select2init">
                                                    <option value="">Select Field Type</option>
                                                    <option value="ckeditor" {{ (isset($productDetailManager->field_type) && $productDetailManager->field_type == 'ckeditor') ? 'selected' : '' }}>CK Editor</option>
                                                    <option value="textbox" {{ (isset($productDetailManager->field_type) && $productDetailManager->field_type == 'textbox') ? 'selected' : '' }}>TextBox</option>
                                                    <option value="textarea" {{ (isset($productDetailManager->field_type) && $productDetailManager->field_type == 'textarea') ? 'selected' : '' }}>Textarea</option>
                                                    
                                                    @if ($errors->has('field_type'))
                                                    <div class=" invalid-feedback">
                                                        {{ $errors->first('field_type') }}
                                                    </div>
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-xl-12 samplePreview">
                                                <label for="content" class="form-label"><span class="text-danger">*
                                                    </span>Sample Preview</label>
                                                <div class="content-input-field d-none">
                                                    <input type="text" class="form-control @error('content') is-invalid @enderror ContentText" id="contentTextbox" name="content" placeholder="Sample Preview" style="height: 100px;">
                                                </div>

                                                <div class="content-textarea-field d-none">
                                                    <textarea class="form-control @error('content') is-invalid @enderror ContentTextArea" id="contentTextarea" name="content" rows="4" placeholder="Sample Preview" style="height: 100px;">{{ isset($productDetailManager->content) ? $productDetailManager->content : old('content') }}</textarea>
                                                </div>

                                                <div class="content-ckeditor-field d-none">
                                                    <textarea class="form-control ck_content @error('content') is-invalid @enderror ContentCkeditor" id="contentCkeditor" name="content" rows="4" placeholder="Sample Preview" style="height: 100px;">{{ isset($productDetailManager->content) ? $productDetailManager->content : old('content') }}</textarea>
                                                </div>

                                                @if ($errors->has('content'))
                                                <div class=" invalid-feedback">
                                                    {{ $errors->first('content') }}
                                                </div>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 border-top border-block-start-dashed d-sm-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>


        </form>
    </div>
</div>

@endsection

@push('scripts')
<!-- Select2 Cdn -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('public/assets/plugin/tagify/tagify.min.js') }}"></script>
<script>
    window.CKEDITOR_BASEPATH = "{{ asset('assets/js/ckeditor/') }}/";
</script>
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<!-- Internal Select-2.js -->
<script src="{{ asset('public/assets/js/select2.js') }}"></script>
<script src="{{ asset('public/assets/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ asset('public/assets/js/custom/product.js') }}"></script>
<script src="{{ asset('public/assets/plugin/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/assets/js/repeater.js')}}"></script>
<script>
    $('.samplePreview').addClass('d-none'); 
    function initContentField() {
        var selectedValue = $('#field_type').val();

        $('.samplePreview').removeClass('d-none'); 
        if (selectedValue === 'textbox') {
              $('.content-ckeditor-field').addClass('d-none');
            $('.content-textarea-field').addClass('d-none'); 
            $('.content-input-field').removeClass('d-none'); 
        } else if (selectedValue === 'textarea') {
             $('.content-ckeditor-field').addClass('d-none');
            $('.content-textarea-field').removeClass('d-none'); 
            $('.content-input-field').addClass('d-none'); 
        } else if (selectedValue === 'ckeditor') {
            $('.content-ckeditor-field').removeClass('d-none');
            $('.content-textarea-field').addClass('d-none'); 
            $('.content-input-field').addClass('d-none'); 
            if (typeof CKEDITOR === 'undefined' || !CKEDITOR || !CKEDITOR.replace) return;

            document.querySelectorAll('textarea.ck_content').forEach(function (textarea) {
                if (!textarea.id) return;

                if (CKEDITOR.instances[textarea.id]) {
                    return;
                }

                CKEDITOR.replace(textarea.id, {
                    enterMode: CKEDITOR.ENTER_BR,
                    allowedContent: true
                });
            });
        }
    }

    $(document).on('change','#field_type',function(){
        initContentField();
    });

</script>

@endpush