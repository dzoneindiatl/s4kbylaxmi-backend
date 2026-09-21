<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-body">
            @if (!empty($product->id))
                <input type="hidden" id="product_id" value="{{ $product->id }}">
            @endif

            <div class="mb-3">
                <label class="form-label">Product Type <span class="text-danger">*</span></label>
                <select class="form-control select2 @error('product_type') is-invalid @enderror product_type_selectbox"
                        name="product_type" id="product_type" required>
                    <option value="">Select</option>
                    <option value="1" {{ old('product_type', $product->product_type ?? '') == '1' ? 'selected' : '' }}>Simple Product</option>
                    <option value="2" {{ old('product_type', $product->product_type ?? '') == '2' ? 'selected' : '' }}>Configured Product</option>
                </select>
                @error('product_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" >
                <label for="form-label">Category/Collection <span class="text-danger">*</span></label>
                <select name="type" class="form-control select2" id="type" onchange="handleCategoryCollectionType()">
                    <option value="">Select</option>
                    <option value="2" @if(isset($product->cat_collection_type)) {{ $product->cat_collection_type == '2' ? 'selected' : '' }} @endif>Category</option>
                    <option value="1" @if(isset($product->cat_collection_type)) {{ $product->cat_collection_type == '1' ? 'selected' : '' }} @endif>Collection</option>
                </select>
            </div>

            <div class="mb-3 d-none" id="collectionBox">
                <label for="" class="form-label">Product Collection</label>
                <select name="product_collection_id" class="form-control select2" id="product_collection_id">
                    <option value="">Select Collection</option>
                    @foreach($productCollection as $collections)
                        <option value="{{ $collections->id }}" {{ old('product_collection_id',$product->main_collection_id ?? '') == $collections->id ? 'selected' : '' }} >{{ $collections->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 d-none" id="categoryBox">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-control select2 @error('main_category_id') is-invalid @enderror"
                        name="main_category_id" id="prdct_category_id"
                        onchange="loadSubCategories()" required>
                    <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}"
                                {{ old('main_category_id', $product->main_category_id ?? '') == $category['id'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                </select>
                @error('main_category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 d-none" id="subCategoryBox">
                <label class="form-label">SubCategory <span class="text-danger">*</span></label>
                <select name="main_sub_category_id" id="prdct_sub_category_id"
                        class="form-control select2" onchange="loadChildCategories()">
                    <option value="">Select Subcategory</option>
                </select>
            </div>

            <div class="mb-3 d-none" id="subChildCategoryBox">
                <label class="form-label">Child Category <span class="text-danger">*</span></label>
                <select name="main_child_cate_id" id="prdct_child_category_id"
                        class="form-control select2" onchange="getVariantData()">
                    <option value="">Select Child Category</option>
                </select>
            </div>

            <div id="variantContainer"></div>
           
            <div class="mb-3 text-end">
                <button type="button" class="btn btn-primary nextBtn" id="nextBtn" onclick="submitProduct()">
                    <span class="btn-text">Save & Continue</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
    .modal .select2-container {
        z-index: 9999;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- Safe JS block --}}
<script>
(function () {

    // Guard  const existingType = $('#type').val();
    window.selectorsData = window.selectorsData || {
        main: '#prdct_category_id',
        sub: '#prdct_sub_category_id',
        child: '#prdct_child_category_id',
    };

    window.preselected = window.preselected || {
        sub: @json($product['main_sub_category_id'] ?? null),
        child: @json($product['main_child_category_id'] ?? null)
    };

    window.resetCategoryFields = function () {

        $('#subCategoryBox').addClass('d-none');
        $('#subChildCategoryBox').addClass('d-none');

        populateSelect(
            selectorsData.sub,
            [],
            "Subcategory"
        );

        populateSelect(
            selectorsData.child,
            [],
            "Child Category"
        );

        $('#variantContainer').html('');
    };

    window.handleCategoryCollectionType = function () {

        const type = $('#type').val();
        $('#variantContainer').html('');

        $('#subCategoryBox').addClass('d-none');
        $('#subChildCategoryBox').addClass('d-none');
        if (type === '1') {
            $('#collectionBox').removeClass('d-none');
            $('#categoryBox').addClass('d-none');
            $('#subCategoryBox').addClass('d-none');
            $('#subChildCategoryBox').addClass('d-none');
            $('#prdct_category_id').val('').trigger('change.select2');
            populateSelect(
                selectorsData.sub,
                [],
                "Subcategory"
            );

            populateSelect(
                selectorsData.child,
                [],
                "Child Category"
            );
            $('#product_collection_id')
                .off('change.collectionVariant')
                .on('change.collectionVariant', function () {
                    const collectionId = $(this).val();
                    if (collectionId) {
                        getVariantData();
                    } else {
                        $('#variantContainer').html('');
                    }
                });
                 const existingCollectionId = $('#product_collection_id').val();

                    if (existingCollectionId) {
                        getVariantData();
                    }
            return;
        }

        if (type === '2') {

            $('#collectionBox').addClass('d-none');
            $('#categoryBox').removeClass('d-none');
            $('#product_collection_id').val('').trigger('change.select2');
            if ($('#prdct_category_id').val()) {
                loadSubCategories();
            }
            return;
        }

        $('#collectionBox').addClass('d-none');
        $('#categoryBox').addClass('d-none');

        $('#product_collection_id').val('').trigger('change.select2');
        $('#prdct_category_id').val('').trigger('change.select2');

        populateSelect(
            selectorsData.sub,
            [],
            "Subcategory"
        );

        populateSelect(
            selectorsData.child,
            [],
            "Child Category"
        );
    };
    window.initSelect2 = function(force = false) {
        $('.select2').each(function () {
            const $el = $(this);

            if (force && $el.data('select2')) {
                try {
                    $el.select2('destroy');
                } catch (e) {
                    console.warn('Select2 destroy failed for', this, e);
                }
            }

            if (!$el.data('select2')) {
                $el.select2({ width: '100%', placeholder: "Select" });
            }
        });
    };

    window.ajaxCall = function(url, data, onSuccess) {
        $.ajax({
            type: 'GET',
            url,
            data,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: onSuccess,
            error: (xhr, status, error) => console.error(`AJAX Error: ${status}: ${error}`)
        });
    }

    window.populateSelect = function(selector, items, placeholder = "Select", selectedId = null) {
        const $el = $(selector);

        // 1. Destroy Select2 if initialized
        if ($el.data('select2')) {
            $el.select2('destroy');
        }

    
        let html = `<option value="">${placeholder}</option>`;

        
        items.forEach(item => {
            if (item.id && item.name) { // ✅ Only include valid options
                const selected = (parseInt(selectedId) === parseInt(item.id)) ? 'selected' : '';
                html += `<option value="${item.id}" ${selected}>${item.name}</option>`;
            }
        });

     
        
        // 3. Set options and reinitialize Select2
        $el.html(html);
        $el.select2({ width: '100%', placeholder });
    };

    window.loadSubCategories = function () {
        const catId = $(selectorsData.main).val();
        $('#subCategoryBox').addClass('d-none');
        $('#subChildCategoryBox').addClass('d-none');
        $('#variantContainer').html('');
        populateSelect(
            selectorsData.sub,
            [],
            "Subcategory"
        );

        populateSelect(
            selectorsData.child,
            [],
            "Child Category"
        );

        if (!catId) {
            return;
        }

        ajaxCall(
            "{{ route('admin-product-ajax-getrelatedsubcategories') }}",
            {
                category_ids: catId
            },
            function (res) {

                if (res.success && res.subcategories.length) {
                    populateSelect(
                        selectorsData.sub,
                        res.subcategories,
                        "Subcategory",
                        preselected.sub
                    );

                    $('#subCategoryBox').removeClass('d-none');
                    if (preselected.sub) {
                        loadChildCategories();
                    }

                } else {

                    $('#subCategoryBox').addClass('d-none');

                    populateSelect(
                        selectorsData.sub,
                        [],
                        "Subcategory"
                    );

                    populateSelect(
                        selectorsData.child,
                        [],
                        "Child Category"
                    );

                    // Directly load variants
                    getVariantData();
                }
            }
        );
    };

    window.loadChildCategories = function () {
        const subCatId = $(selectorsData.sub).val();
        $('#subChildCategoryBox').addClass('d-none');
        populateSelect(
            selectorsData.child,
            [],
            "Child Category"
        );
        $('#variantContainer').html('');
        if (!subCatId) {
            return;
        }
        ajaxCall(
            "{{ route('admin-product-ajax-getchildcategory') }}",
            {
                subctgids: subCatId
            },
            function (res) {
                console.log('Child Categories:', res.childcat);
                if (res.success && res.childcat.length) {
                    populateSelect(
                        selectorsData.child,
                        res.childcat,
                        "Child Category",
                        preselected.child
                    );

                    $('#subChildCategoryBox').removeClass('d-none');
                    if (preselected.child) {
                        getVariantData();
                    }

                } else {
                    $('#subChildCategoryBox').addClass('d-none');
                    populateSelect(
                        selectorsData.child,
                        [],
                        "Child Category"
                    );
                    getVariantData();
                }
            }
        );
    };

    window.getVariantData = function() {
        var subchildCategory = $('#prdct_child_category_id').val();
        var productType = $('#product_type').val(); 
        const $btn = $('.nextBtn'); 
        const originalHtml = $btn.html(); 
         const type = $('#type').val();
        const formData = {
             _token: '{{ csrf_token() }}',
            product_type: $('#product_type').val(),
            type: type,
            main_category_id: $('#prdct_category_id').val(),
            main_sub_category_id: $('#prdct_sub_category_id').val(),
            main_child_cate_id : subchildCategory,
            main_collection_id : $('#product_collection_id').val(), 
        }; 
        const productId = $('#product_id').val();
        if (productId) {
            formData.product_id = productId;
        }

        if(productType == 2){
            $.ajax({
                url:'{{ route("admin-product-get-variant-record") }}', 
                method:"POST", 
                dataType:'json',
                data:formData, 
                success:function(response){
                    console.log(response); 
                    if(response.success){
                         $('#variantContainer').html(response.html); 
                    }
                },
                error:function(err){
                    console.log(err); 
                }
            }); 
        }
    }
    
    window.submitProduct = function() {
        const $btn = $('.nextBtn');
        const originalHtml = $btn.html(); 
        const formData = new FormData();

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('product_type', $('#product_type').val());
        formData.append('cat_collection_type',$('#type').val()); 
        formData.append('main_category_id', $('#prdct_category_id').val());
        formData.append('main_sub_category_id', $('#prdct_sub_category_id').val());
        formData.append('main_child_cate_id', $('#prdct_child_category_id').val());
        formData.append('product_collection_id',$('#product_collection_id').val()); 
        var variantSelected = false;
        $('.variant-card').each((i, el) => {
            const variantId = $(el).find('.variantSelect').val();
            const values = $(el).find('.variantValuesSelect').val() || [];
            if (variantId) {  
                variantSelected = true;          
                formData.append(`variant[${i}]`, variantId);
                values.forEach(v => formData.append(`variant_values[${i}][]`, v));
            }
        });
        const productId = $('#product_id').val();
        if (productId) {
            formData.append('product_id',productId); 
        }
        $.ajax({
            url: '{{ route("admin-product-save.step1") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            // 🔽 BEFORE AJAX SEND
            beforeSend: function () {
                $btn.prop('disabled', true).html(`
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Processing...
                `);
            },

            // 🔽 ON SUCCESS
            success: function (res) { 
                console.log(res); 
                if (res.success) {
                    $('#tab2').html(res.varient);
                } else {
                    alert(res.message || "Something went wrong");
                }
            },

            // 🔽 ON ERROR
            error: function (xhr) {
                console.error(xhr.responseText);
               // alert("Validation error or server error");
               $btn.prop('disabled', false).html(originalHtml);
            },

            // 🔽 ALWAYS RUN (AFTER success/error)
            complete: function () {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
       // $btn.prop('disabled', false).html(originalHtml);
    }

    $(document).ready(() => {
        initSelect2(true);
        const existingType = $('#type').val();

        if (existingType) {
            handleCategoryCollectionType();
        }

        console.log("variant record------",$(selectorsData.main).val());
        if ($(selectorsData.main).val()) {
            loadSubCategories();
        }

        $(document).on('change', 'select', function () {
            const id = $(this).attr('id');
            $(`label.error[for="${id}"]`).remove();
            $(this).removeClass('is-invalid');
        });

        $('.modal').on('shown.bs.modal', function () {
            setTimeout(() => {
                initSelect2(true);
                if ($(selectorsData.main).val()) {
                    loadSubCategories(); // Also run on modal open
                }
            }, 100);
        });
    });
})();
</script>
