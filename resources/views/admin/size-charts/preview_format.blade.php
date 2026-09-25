@if($result->chart_format == 'tabular')
    @foreach($result->sections as $section)
        <div class="mb-4">
            <h5 class="mb-3">{{ ucfirst($section->section_type) }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Measurement</th>
                            @foreach($result->sizes as $size)
                                <th>{{ $size->size_name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($section->measurements as $measurement)
                            <tr>
                                <td>
                                    {{ ucfirst($measurement->measurement_name) }}
                                </td>
                                @foreach($result->sizes as $size)
                                    @php
                                        $value = $measurement->values->where('size_id', $size->id)->first();
                                    @endphp
                                    <td>
                                        @if($unit == 'inch')
                                            {{ $value?->value_inch ?? '-' }}
                                        @else
                                            {{ $value?->value_cm ?? '-' }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@elseif($result->chart_format == 'card/box')
    @foreach($result->sections as $section)
        <div class="mb-4">
            <h5 class="mb-3">{{ ucfirst($section->section_type) }}</h5>
            <div class="row">
                @foreach($section->measurements as $measurement)
                    <div class="col-md-4 col-lg-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-center mb-3">
                                    {{ ucfirst($measurement->measurement_name) }}
                                </h6>
                                @foreach($result->sizes as $size)
                                    @php
                                        $value = $measurement->values->where('size_id', $size->id)->first();
                                    @endphp
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span>{{ $size->size_name }}</span>
                                        <strong>
                                            @if($unit == 'inch')
                                                {{ $value?->value_inch ?? '-' }}
                                            @else
                                                {{ $value?->value_cm ?? '-' }}
                                            @endif
                                        </strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

@elseif($result->chart_format == 'accordian')
    <div class="accordion" id="sizeChartAccordion{{ $result->id }}{{ $unit }}">
        @foreach($result->sections as $section)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $result->id }}{{ $unit }}{{ $loop->index }}">

                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $result->id }}{{ $unit }}{{ $loop->index }}">
                        {{ ucfirst($section->section_type) }}
                    </button>
                </h2>
                <div id="collapse{{ $result->id }}{{ $unit }}{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#sizeChartAccordion{{ $result->id }}{{ $unit }}">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Measurement</th>
                                        @foreach($result->sizes as $size)
                                            <th>{{ $size->size_name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($section->measurements as $measurement)
                                        <tr>
                                            <td>
                                                {{ ucfirst($measurement->measurement_name) }}
                                            </td>

                                            @foreach($result->sizes as $size)
                                                @php
                                                    $value = $measurement->values->where('size_id', $size->id)->first();
                                                @endphp
                                                <td>
                                                    @if($unit == 'inch')
                                                        {{ $value?->value_inch ?? '-' }}
                                                    @else
                                                        {{ $value?->value_cm ?? '-' }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@elseif($result->chart_format == 'horizontal_size_selector')
    @foreach($result->sections as $section)
        <div class="mb-4">
            <h5 class="mb-3">
                {{ ucfirst($section->section_type) }}
            </h5>

            <div class="d-flex flex-wrap gap-2 mb-3 size-selector" data-chart="{{ $result->id }}" data-unit="{{ $unit }}">
                @foreach($result->sizes as $size)
                    <button type="button" class="btn btn-outline-primary size-select-btn {{ $loop->first ? 'active' : '' }}" data-size-id="{{ $size->id }}">
                        {{ $size->size_name }}
                    </button>
                @endforeach
            </div>
            @foreach($result->sizes as $size)
                <div class="size-content {{ $loop->first ? '' : 'd-none' }}" data-size-content="{{ $size->id }}">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Measurement</th>
                                    <th>{{ $size->size_name }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($section->measurements as $measurement)
                                    @php
                                        $value = $measurement->values->where('size_id', $size->id)->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ ucfirst($measurement->measurement_name) }}
                                        </td>
                                        <td>
                                            @if($unit == 'inch')
                                                {{ $value?->value_inch ?? '-' }}
                                            @else
                                                {{ $value?->value_cm ?? '-' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach


@elseif($result->chart_format == 'size_comparison_scale')
    @foreach($result->sections as $section)
        <div class="mb-4">
            <h5 class="mb-3">
                {{ ucfirst($section->section_type) }}
            </h5>

            @foreach($section->measurements as $measurement)
                <div class="mb-4">
                    <div class="fw-bold mb-2">
                        {{ ucfirst($measurement->measurement_name) }}
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($result->sizes as $size)
                            @php
                                $value = $measurement->values
                                    ->where('size_id', $size->id)
                                    ->first();
                            @endphp
                            <div class="border rounded p-2 text-center" style="min-width:80px;">
                                <div class="fw-bold">
                                    {{ $size->size_name }}
                                </div>
                                <div>
                                    @if($unit == 'inch')
                                        {{ $value?->value_inch ?? '-' }}
                                    @else
                                        {{ $value?->value_cm ?? '-' }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@else
    <div class="alert alert-warning">
        Chart format is not selected.
    </div>

@endif
