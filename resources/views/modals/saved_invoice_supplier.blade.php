<div class="modal fade" id="saved-invoice" tabindex="-1" data-bs-backdrop="static" aria-labelledby="saved-invoice"
    data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenteredScrollableTitle">
                    Search Invoice
                </h1>
            </div>
            <div class="modal-body">
                <form id="filter-order" method="post">
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                @php
                                    use App\Models\Suppliers;
                                    $suppliers = Suppliers::select('name')->get();
                                @endphp
                                <select @required(true) class="form-select" name="worker" id="worker">
                                    <option selected value="{{ $supplier->name }}"> {{ $supplier->name }} </option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->name }}">
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <input type="date" class="form-control d-inline-block" style="width: auto"
                                value="{{ Date('Y-m-d') }}" max="{{ Date('Y-m-d') }}" name="date" id="date" />
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary d-inline-block ripple-surface">Search</button>
                        </div>
                    </div>
                </form>
                <div class="d-flex justify-content-center d-none">
                    <div class="alert alert-danger text-center" style="max-width: 50%!important" id="filter-response">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary ripple-surface" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#filter-order').on('submit', function(e) {
        e.preventDefault();
        var selected_supplier = e.currentTarget[0].value,
            date = e.currentTarget[1].value;
        $.ajax({
            url: '/orders/suppliers/edit',
            data: {
                _token: "{{ csrf_token() }}",
                supplier: selected_supplier,
                date: date
            },
            success: function(data) {
                console.log(data);
                if (data.data) {
                    var res = data.data;
                    window.open(res, '_self');
                } else if (data.empty) {
                    alert(data.empty);
                }
            },
            error: function(err) {
                console.log(err);
            }
        })
    })
</script>
