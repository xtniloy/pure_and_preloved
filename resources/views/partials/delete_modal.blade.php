<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure want to delete this <span id="element-type"></span>: <span id="element-name"
                                                                                           class="font-weight-bold text-danger"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>

                <form method="post" id="set-action">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="company_id" class="company">
                    <button type="submit" class="btn btn-danger d-inline" id="modalBtnDelete">
                        <i class="align-middle feather-fix" data-feather="trash"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $('.btnDelete').on('click', function () {
            let type = $(this).data('type');
            let name = $(this).data('name');
            let url = $(this).data('route');

            $('#set-action').attr('action', url);

            $('#element-type').html(type);

            $('#element-name').html(name);
            $('#deleteModal').modal('show');
        });
    </script>
@endpush
