@if($results->isNotEmpty())
@forelse($results as $result)
<tr class="list-data-row items-inner" data-total-count="{{ $totalResults }}" data-id = "{{ $result->id }}">

    <td>{{ $result->section_name ?? "N/A" }}</td>
    <td>{{ $result->field_type ?? "N/A" }}</td>
    <td>{{ $result->order ?? "N/A" }}</td>
    <td>
        <a href="{{ route('admin-product-detail-manager.edit', $result->id) }}" class="edit-btn" title="Edit">
            <i class="ri-edit-line"></i>
        </a>
        <a href="{{ route('admin-product-detail-manager.delete', $result->id) }}" class="delete-btn" title="Delete" data-bs-toggle="tooltip" data-bs-placement="top" onclick="return confirm('Are you sure you want to delete this product detail section?')">
            <i class="ri-delete-bin-line"></i>
        </a>
    </td>
</tr>
@empty
@endforelse
@else
<tr class="noresults-row">
    <td colspan="8" style="text-align: center;">No results found.</td>
</tr>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alerts.js') }}"></script>
<script type="text/javascript">
    new Sortable(powerwidgets, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: function(evt) {
            var counter = 1;
            var requestData = [];
            $(".items-inner").each(function() {
                requestData.push({
                    "id": $(this).attr("data-id"),
                    "order": counter
                });
                counter++;
            });

            $.ajax({
                url: '{{ Route('admin-product-detail-manager.updateOrder') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "requestData": requestData
                },
                success: function(response) {
                    Swal.fire({
                        title: "Success",
                        text: "Product Detail Order updated successfully!",
                        icon: "success"
                    });
                    window.location.reload();
                }
            });
        },
    });
</script>