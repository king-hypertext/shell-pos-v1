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
                                    use App\Models\Customers;
                                    $customers = Customers::select('name')->get();
                                @endphp
                                <select @required(true) class="form-select" name="worker" id="worker">
                                    <option selected value=""> Select Customer </option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->name }}">
                                            {{ $customer->name }}
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
        console.log(e);
        var selected_worker = e.currentTarget[0].value,
            date = e.currentTarget[1].value;
        console.log(selected_worker, date);
        $.ajax({
            url: '/invoices/edit',
            data: {
                _token: "{{ csrf_token() }}",
                worker: selected_worker,
                date: date
            },
            success: function(data) {
                console.log(data);
                if (data.data) {
                    var res = data.data;
                    window.open(res, '_blank');
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
