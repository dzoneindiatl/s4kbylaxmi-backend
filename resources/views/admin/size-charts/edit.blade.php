@extends('admin.layout.master')

@push('styles')
<link href="{{ asset('assets/plugin/tagify/tagify.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
@endpush
@section('content')
@include('admin.layout.response_message')
<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <a class="btn btn-dark" href="{{ url()->previous() }}">Back</a>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Size Chart</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header Close -->

<div class="row">
    <div class="col-xl-12">
        <form action="{{route('admin-'.$model.'.update',base64_encode($size_charts->id))}}" method="post" id="shippingcompanyForm"
            enctype="multipart/form-data">
            @csrf
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Edit Size Chart
                    </div>
                </div>
            </div>

            <div id="collapseSizeChart" aria-labelledby="headingSizeChart" data-bs-parent="#categoryAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <label for="chart_title" class="form-label">Chart Title</label>
                            <input type="text" class="form-control" id="chart_title" name="chart_title" placeholder="Enter Chart Title" value="{{ old('chart_title', $size_charts->title ?? '') }}">
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label for="chart_format" class="form-label">Chart Format</label>
                            <select name="chart_format" class="form-control" id="">
                                <option value="">Select</option>
                                <option value="tabular" {{ $size_charts->chart_format == 'tabular' ? 'selected' : '' }}>tabular Format</option>
                                <option value="card/box" {{ $size_charts->chart_format == 'card/box' ? 'selected' : ''  }}>Card/Box Format</option>
                                <option value="accordian" {{ $size_charts->chart_format == 'accordian' ? 'selected' : '' }}>Accordian Format</option>
                                <option value="horizontal_size_selector" {{ $size_charts->chart_format == 'horizontal_size_selector' ? 'selected' : '' }}>Horizontal Size Selector</option>
                                <option value="size_comparison_scale" {{ $size_charts->chart_format == 'size_comparison_scale' ? 'selected' : '' }}>Size Comparison Scale</option>
                            </select>
                        </div>
                        <div class="col-xl-10 mb-3">
                            <label for="mesurement_type_inch" class="form-label">Inch </label>
                            <input type="radio" id="mesurement_type_inch" name="mesurement_type" value="inch" onclick="changeMesurementType('inch')" {{ old('mesurement_type', 'inch') == 'inch' ? 'checked' : '' }}>
                            <label for="mesurement_type_cm" class="form-label">CM </label>
                            <input type="radio" id="mesurement_type_cm" name="mesurement_type" value="cm" onclick="changeMesurementType('cm')" {{ old('mesurement_type') == 'cm' ? 'checked' : '' }}>
                        </div>
                        <div class="col-xl-10 mb-3 mesurement_type_inch_div" id="">
                            <div class="table-responsive">
                                <table id="sizeChartTableUpperInch" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Upper</th>
                                            <th>XS</th>
                                            <th>S</th>
                                            <th>M</th>
                                            <th>L</th>
                                            <th>XL</th>
                                            <th>2XL</th>
                                            <th>3XL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $upperSection = $size_charts->sections->firstWhere('section_type', 'upper'); 
                                            $upperMeasurements = $upperSection?->measurements ?? collect(); 
                                        @endphp
                                        @foreach($upperMeasurements as $measurement) 
                                            <tr data-measurement-id="{{ $measurement->id }}"> 
                                                <td> 
                                                    <input type="hidden" name="upper_measurement_id[]" value="{{ $measurement->id }}"> 
                                                    <input type="text" name="upper_type[]" class="form-control" value="{{ $measurement->measurement_name }}" placeholder="e.g., chest, shoulder"> 
                                                </td> 
                                                @foreach($size_charts->sizes as $size) 
                                                    @php 
                                                        $value = $measurement->values ->where('size_id', $size->id) ->first(); 
                                                    @endphp 
                                                    <td> 
                                                        <input type="number" name="upper_size_{{ strtolower($size->size_name) }}[]" min="0" step="0.0001" class="form-control" value="{{ $value?->value_inch }}"> 
                                                    </td> 
                                                @endforeach 
                                                <td> 
                                                    <button type="button" class="btn btn-danger removeRowUpper"> X </button> 
                                                </td> 
                                            </tr> 
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" id="addRowUpperInch" class="btn btn-primary">Add More</button>
                            </div>
                        </div>
                        <div class="col-xl-10 mb-3 mesurement_type_inch_div">
                            <div class="table-responsive">
                                <table id="sizeChartTableBottomInch" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Bottom</th>
                                            <th>XS</th>
                                            <th>S</th>
                                            <th>M</th>
                                            <th>L</th>
                                            <th>XL</th>
                                            <th>2XL</th>
                                            <th>3XL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $bottomSection = $size_charts->sections->firstWhere('section_type', 'bottom'); 
                                            $bottomMeasurements = $bottomSection?->measurements ?? collect(); 
                                        @endphp 
                                        @foreach($bottomMeasurements as $measurement) 
                                            <tr data-measurement-id="{{ $measurement->id }}"> 
                                                <td> 
                                                    <input type="hidden" name="bottom_measurement_id[]" value="{{ $measurement->id }}"> 
                                                    <input type="text" name="bottom_type[]" class="form-control" value="{{ $measurement->measurement_name }}" placeholder="e.g., waist, knee"> 
                                                </td> 
                                                @foreach($size_charts->sizes as $size) 
                                                    @php 
                                                        $value = $measurement->values ->where('size_id', $size->id) ->first(); 
                                                    @endphp 
                                                    <td> 
                                                        <input type="number" name="bottom_size_{{ strtolower($size->size_name) }}[]" min="0" step="0.0001" class="form-control" value="{{ $value?->value_inch }}"> 
                                                    </td> 
                                                @endforeach 
                                                <td> 
                                                    <button type="button" class="btn btn-danger removeRowBottom"> X </button> 
                                                </td> 
                                            </tr> 
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" id="addRowBottomInch" class="btn btn-primary">Add More</button>
                            </div>
                        </div>
                        <div class="col-xl-10 mb-3 mesurement_type_cm_div" style="display:none;">
                            <div class="table-responsive">
                                <table id="sizeChartTableUpperCM" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Upper</th>
                                            <th>XS</th>
                                            <th>S</th>
                                            <th>M</th>
                                            <th>L</th>
                                            <th>XL</th>
                                            <th>2XL</th>
                                            <th>3XL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upperMeasurements as $measurement) 
                                            <tr data-measurement-id="{{ $measurement->id }}"> 
                                                <td> 
                                                    <input type="hidden" name="upper_measurement_id[]" value="{{ $measurement->id }}"> 
                                                    <input type="text" name="upper_type_cm[]" class="form-control" value="{{ $measurement->measurement_name }}" readonly> 
                                                </td> 
                                                @foreach($size_charts->sizes as $size) 
                                                    @php 
                                                        $value = $measurement->values ->where('size_id', $size->id) ->first(); 
                                                    @endphp 
                                                    <td> 
                                                        <input type="number" name="upper_size_cm_{{ strtolower($size->size_name) }}[]" min="0" step="0.0001" class="form-control" value="{{ $value?->value_cm }}"> 
                                                    </td> 
                                                @endforeach 
                                                <td> 
                                                    <button type="button" class="btn btn-danger removeRowUpperCM"> X </button> 
                                                </td> 
                                            </tr> 
                                        @endforeach  
                                    </tbody>
                                </table>
                                <button type="button" id="addRowUpper" class="btn btn-primary">Add More</button>
                            </div>
                        </div>
                        <div class="col-xl-10 mb-3 mesurement_type_cm_div" style="display:none;">
                            <div class="table-responsive">
                                <table id="sizeChartTableBottomCM" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Bottom</th>
                                            <th>XS</th>
                                            <th>S</th>
                                            <th>M</th>
                                            <th>L</th>
                                            <th>XL</th>
                                            <th>2XL</th>
                                            <th>3XL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         @foreach($bottomMeasurements as $measurement)
                                            <tr data-measurement-id="{{ $measurement->id }}">
                                                <td>
                                                    <input type="text" name="bottom_type_cm[]" class="form-control" value="{{ $measurement->measurement_name }}" readonly>
                                                </td>

                                                @foreach($size_charts->sizes as $size)
                                                    @php
                                                        $value = $measurement->values->where('size_id', $size->id)->first();
                                                    @endphp

                                                    <td>
                                                        <input type="number" name="bottom_size_cm_{{ strtolower($size->size_name) }}[]" min="0" step="0.0001" class="form-control" value="{{ $value?->value_cm }}">
                                                    </td>
                                                @endforeach

                                                <td>
                                                    <button type="button" class="btn btn-danger removeRowBottomCM">X</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" id="addRowBottom" class="btn btn-primary">Add More</button>
                            </div>
                        </div>
                        <div class="col-xl-10 mb-3">
                            <div class="form-group">
                                <label for="">Chart Image</label>
                                <input type="file" name="chart_image" class="form-control">
                            </div>
                        </div>
                        <div class="col-xl-12 mb-3">
                            <label for="chart_description" class="form-label">Chart Description</label>
                            <textarea class="form-control" name="chart_description" id="chart_description" cols="30" rows="5">{{ old('chart_description', $chart_content->description ?? '') }}</textarea>
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
<script src="{{ asset('assets/plugin/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Internal Select-2.js -->
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alerts.js') }}"></script>
<script src="{{ asset('assets/js/form-validation.js') }}"></script>
<script src="{{ asset('assets/plugin/tagify/tagify.min.js') }}"></script>
<script src="{{ asset('assets/js/custom/category.js') }}"></script>

<script>
    ['chart_description'].forEach(id => {
        CKEDITOR.replace(id, {
            filebrowserUploadUrl: '{{ URL()->to("base/uploder") }}',
            enterMode: CKEDITOR.ENTER_BR,
            allowedContent: true
        });
    });
</script>

```html
<script>
const inchToCm = 2.54;

function syncCMTable(inchSelector, cmSelector, nameReplace) {
    const $cmBody = $(cmSelector).find('tbody').empty();

    $(inchSelector).find('tbody tr').each(function () {
        const $row = $('<tr/>');

        $(this).find('input').each(function (i) {
            if ($(this).attr('type') === 'hidden') {
                return;
            }

            let val = $(this).val();
            let name = $(this).attr('name');

            if (!name) {
                return;
            }

            name = name.replace(nameReplace.from, nameReplace.to);

            val = i === 0
                ? val
                : (val ? (parseFloat(val) * inchToCm).toFixed(2) : '');

            $row.append(`
                <td>
                    <input type="${i === 0 ? 'text' : 'number'}"
                           min="0"
                           step="0.0001"
                           name="${name}"
                           class="form-control"
                           value="${val}"
                           ${i === 0 ? 'readonly' : ''}>
                </td>
            `);
        });

        $row.append(`
            <td>
                <button type="button"
                        class="btn btn-danger ${cmSelector.includes('Upper') ? 'removeRowUpperCM' : 'removeRowBottomCM'}">
                    X
                </button>
            </td>
        `);

        $cmBody.append($row);
    });
}

function addRow(typePrefix, tableId) {
    const sizes = ['xs', 's', 'm', 'l', 'xl', '2xl', '3xl'];

    const sizeFields = sizes.map(size => `
        <td>
            <input type="number"
                   name="${typePrefix}_size_${size}[]"
                   min="0"
                   step="0.0001"
                   class="form-control">
        </td>
    `).join('');

    const row = `
        <tr data-measurement-id="">
            <td>
                <input type="hidden"
                       name="${typePrefix}_measurement_id[]"
                       value="">

                <input type="text"
                       name="${typePrefix}_type[]"
                       class="form-control"
                       placeholder="e.g., chest, waist">
            </td>

            ${sizeFields}

            <td>
                <button type="button"
                        class="btn btn-danger removeRow${typePrefix === 'upper' ? 'Upper' : 'Bottom'}">
                    X
                </button>
            </td>
        </tr>
    `;

    $(`#${tableId} tbody`).append(row);

    const cmTableId = typePrefix === 'upper'
        ? 'sizeChartTableUpperCM'
        : 'sizeChartTableBottomCM';

    syncCMTable(`#${tableId}`, `#${cmTableId}`, {
        from: `${typePrefix}_size_`,
        to: `${typePrefix}_size_cm_`
    });
}

function changeMesurementType(type) {
    $('.mesurement_type_inch_div').toggle(type === 'inch');
    $('.mesurement_type_cm_div').toggle(type !== 'inch');
}

window.changeMesurementType = changeMesurementType;

$(document)
    .on('input', '#sizeChartTableUpperInch input', function () {
        if ($(this).attr('type') === 'hidden') {
            return;
        }

        syncCMTable('#sizeChartTableUpperInch', '#sizeChartTableUpperCM', {
            from: 'upper_size_',
            to: 'upper_size_cm_'
        });
    })

    .on('input', '#sizeChartTableBottomInch input', function () {
        if ($(this).attr('type') === 'hidden') {
            return;
        }

        syncCMTable('#sizeChartTableBottomInch', '#sizeChartTableBottomCM', {
            from: 'bottom_size_',
            to: 'bottom_size_cm_'
        });
    })

    .on('click', '#addRowUpperInch', function () {
        addRow('upper', 'sizeChartTableUpperInch');
    })

    .on('click', '#addRowBottomInch', function () {
        addRow('bottom', 'sizeChartTableBottomInch');
    })

    .on('click', '#addRowUpper', function () {
        addRow('upper', 'sizeChartTableUpperCM');
    })

    .on('click', '#addRowBottom', function () {
        addRow('bottom', 'sizeChartTableBottomCM');
    })

    .on('click', '.removeRowUpper', function () {
        const $row = $(this).closest('tr');
        const index = $row.index();

        $row.remove();

        $('#sizeChartTableUpperCM tbody tr').eq(index).remove();
    })

    .on('click', '.removeRowBottom', function () {
        const $row = $(this).closest('tr');
        const index = $row.index();

        $row.remove();

        $('#sizeChartTableBottomCM tbody tr').eq(index).remove();
    })

    .on('click', '.removeRowUpperCM', function () {
        const index = $(this).closest('tr').index();

        $('#sizeChartTableUpperInch tbody tr').eq(index).remove();
        $('#sizeChartTableUpperCM tbody tr').eq(index).remove();
    })

    .on('click', '.removeRowBottomCM', function () {
        const index = $(this).closest('tr').index();

        $('#sizeChartTableBottomInch tbody tr').eq(index).remove();
        $('#sizeChartTableBottomCM tbody tr').eq(index).remove();
    });

$(document).ready(function () {
    const measurementType = $('input[name="mesurement_type"]:checked').val() || 'inch';

    changeMesurementType(measurementType);
});
</script>
@endpush