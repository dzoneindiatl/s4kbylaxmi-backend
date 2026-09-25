@if($results->isNotEmpty())
@forelse($results as $result)
<tr class="list-data-row items-inner" data-total-count="{{$totalResults}}" data-id = "{{$result->id}}">
    <td>{{ $result->title ?? "N/A" }}</td>
    <td> {{ ucwords(str_replace('_',' ',$result->chart_format)) ?? "N/A" }} </td>
    <td>{{ date('Y-m-d',strtotime($result->created_at)) }}</td>
    <td>
        @if($result->status == '1')
        <span class="badge bg-success">Activated</span>
        @else
        <span class="badge bg-danger">Deactivated</span>
        @endif
    </td>

    <td>
        <div class="hstack gap-2 flex-wrap">
            {{-- @if($result->status == 1)
                <a href='{{route("admin-size-charts.status",array($result->id,0))}}' class="btn btn-danger" id="deactivate-button"><i class="ri-close-line"></i></a>
            @else
                <a href='{{route("admin-size-charts.status",array($result->id,1))}}' class="btn btn-success" id="activate-button"><i class="ri-check-line"></i></a>
            @endif --}}
            <a href="{{ route('admin-size-charts.edit', base64_encode($result->id)) }}" class="btn btn-info" title="Edit Product"><i class="ri-edit-line"></i></a>
            <button type="button" class="btn previewCmBtn"data-bs-toggle="modal" data-bs-target="#centimeterPreviewModal{{ $result->id }}" title="Preview">
                <i class="fa-solid fa-eye" style="color:red;"></i>
            </button>
        </div>
    </td>
</tr>
@empty
@endforelse
@else
<tr class="noresults-row">
    <td colspan="7" style="text-align: center;">No results found.</td>
</tr>
@endif
