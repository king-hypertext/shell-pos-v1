@extends('app.index')
@section('content')
    <div class="container-fluid mt-4 ">
        <h2 class="h2 text-uppercase">All suppliers</h2>
        <div class="d-flex justify-content-end me-1 mb-2">
            {{-- <a href="{{ route('supplier.index') }}" target="_blank" role="button" class="btn btn-success me-2">
            create invoice
        </a> --}}
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-addSupplier">Add
                Supplier
            </button>
        </div>
        <style>
            td {
                margin-top: 0;
                padding-top: 0 !important;
                padding-right: 0 !important;
                vertical-align: middle;
            }
        </style>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="d-flex justify-content-center">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php
            use Carbon\Carbon;
        @endphp
        <div class="table-responsive">
            <table class="table table-hover" id="supplier-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">SUPPLIER</th>
                        <th scope="col">CATEGORY</th>
                        <th scope="col">CONTACT</th>
                        <th scope="col">ADDRESS</th>
                        <th>CREATED AT</th>
                        <th class="px-0">ADD ORDER</th>
                        <th scope="col">ACTIONS</th>
                        {{-- <th scope="col">EDIT</th> --}}
                        {{-- <th scope="col">DELETE</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $index => $supplier)
                        <tr class="text-uppercase">
                            <td scope="row">{{ $index + 1 }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td class="text-uppercase">{{ $supplier->category }}</td>
                            <td>{{ $supplier->contact }}</td>
                            <td>{{ $supplier->address }}</td>
                            <td>{{ Carbon::parse($supplier->created_at)->format('Y-M-d') }}</td>
                            <td class="pe-0 mx-auto">
                                <a href="{{ route('supplier.show', [$supplier->id]) }}" type="button"
                                    class="btn text-lowercase " title="create order" rel="noopener noreferrer"><i
                                        class="fas fa-plus-circle"></i>
                                </a>
                            </td>
                            <td>
                                <form action='{{ route('suppliers.destroy', [$supplier->id]) }}' method="post"
                                    class="d-inline">
                                    @method('DELETE')
                                    <button type="button" id="{{ $supplier->id }}" class="btn text-primary btn_edit"
                                        title="Edit supplier {{ $supplier->name }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $supplier->id }}" readonly>
                                    <button type="button" id="delete_supplier" class="btn text-danger"
                                        title="delete {{ $supplier->name }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- edit supplier modal --}}
    <div class="modal fade" id="modal-edit-supplier" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="modal-edit-supplier" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-2">
                    <div class="row justify-content-center">
                        <div class="divider my-0">
                            <div class="divider-text">
                                <h5 class="h3 text-capitalize" id="modal-title"></h5>
                            </div>
                        </div>
                        <form class="px-5 py-2" id="form-edit-supplier" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="supplier-id" />
                            <div class="form-outline mb-3">
                                <input type="text" name="edit-supplier" id="supplierName" class="form-control" />
                                <label class="form-label" for="supplierName">Supplier Name</label>
                            </div>
                            <div class="form-outline mb-3">
                                <select name="edit-category" id="category" class="form-select">
                                    <option value="allied">ALLIED</option>
                                    <option value="shell">SHELL</option>
                                </select>
                            </div>
                            <div class="form-outline mb-4">
                                <input required type="text" name="edit-contact" id="contact" class="form-control" />
                                <label class="form-label" for="contact">Supplier's Contact</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input required type="text" name="edit-address" id="supplierAddress"
                                    class="form-control" />
                                <label class="form-label" for="supplierAddress">Supplier's Address</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update Supplier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- add supplier modal --}}
    <div class="modal fade" id="modal-addSupplier" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="modal-addSupplier" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-2">
                    <div class="row justify-content-center">
                        <div class="divider my-0">
                            <div class="divider-text">
                                <h5 class="h3 text-capitalize text-center ">Add New Supplier</h5>
                            </div>
                        </div>
                        <form class="px-5 py-2" id="addSupplier" method="POST" autocomplete="off">
                            <div class="h6 alert alert-danger alert-dismissible text-center error-msg"
                                style="display: none">
                                error
                            </div>
                            <div class="h6 alert alert-success alert-dismissible text-center success-msg"
                                style="display: none">
                                success
                            </div>
                            @csrf
                            <div class="form-outline mb-3">
                                <input required type="text" name="supplier-name" id="supplierName"
                                    class="form-control" />
                                <label class="form-label" for="supplierName">Supplier Name</label>
                            </div>
                            <div class="form-outline mb-3">
                                <select required name="category" id="category" class="form-select">
                                    <option value="" selected>Select Category</option>
                                    <option value="allied">ALLIED</option>
                                    <option value="shell">SHELL</option>
                                </select>
                            </div>
                            <div class="form-outline mb-4">
                                <input required type="text" name="contact" id="contact" class="form-control" />
                                <label class="form-label" for="contact">Supplier's Contact</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input required type="text" name="address" id="supplierAddress"
                                    class="form-control" />
                                <label class="form-label" for="supplierAddress">Supplier's Address</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Add Supplier</button>
                        </form>
                    </div>
                </div>
                <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"
                        onclick="location.reload()">Discard</button>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script type="text/javascript">
            const showSuccessAlert = Swal.mixin({
                position: 'top-end',
                toast: true,
                timer: 6500,
                showConfirmButton: false,
                timerProgressBar: false,
            });
            showSuccessAlert.fire({
                icon: 'success',
                text: '{{ session('success') }}',
                padding: '10px',
                width: 'auto'
            });
        </script>
    @endif
@endsection
@section('script')
    <script>
        $('button#delete_supplier').on('click', e => {
            var form = e.currentTarget.form;
            Swal.fire({
                title: "Confirm Delete!",
                text: "Are you sure you want to delete?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
            console.log(e.currentTarget.form);
        });
        $(document).ready(function() {
            ;
            $('form#form-edit-supplier').on('submit', (e) => {
                e.target.action = '/suppliers/' + e.currentTarget[2].value;
                return true;
            });

            $('form#addSupplier').on('submit', (e) => {
                e.preventDefault();
                console.log(e);
                // break;
                $.ajax({
                    url: '/suppliers',
                    type: 'POST',
                    data: {
                        _token: e.currentTarget[0].value,
                        name: e.currentTarget[1].value,
                        category: e.currentTarget[2].value,
                        address: e.currentTarget[4].value,
                        contact: e.currentTarget[3].value,
                    },
                    success: (res) => {
                        if (res.success) {
                            $('.success-msg').text(res.success);
                            $('.success-msg').show();
                            e.target.reset();
                            setTimeout(() => {
                                $('.success-msg').hide();
                            }, 3000);
                        }
                    },
                    error: (error) => {
                        $('.error-msg').text(error.responseJSON.message);
                        $('.error-msg').show();
                        setTimeout(() => {
                            $('.error-msg').hide();
                        }, 3000);
                    }
                })
            })
            $(document).on('click', 'button.btn_edit', function(e) {
                var supplier_id = e.currentTarget.id;
                var contact = $('input[name="edit-contact"]'),
                    name = $('input[name="edit-supplier"]'),
                    category = $('select[name="edit-category"]')
                address = $('input[name="edit-address"]');
                modal_title = $('#modal-title')[0];
                $.ajax({
                    url: "/suppliers/" + supplier_id,
                    success: function(res) {
                        console.log(res);
                        $('#modal-edit-supplier').modal('show');
                        $('#supplier-id').val(res.id);
                        name.val(res.name).addClass('active');
                        contact.val(res.contact).addClass('active');
                        address.val(res.address).addClass('active');
                        category.val(res.category).addClass('active');
                        modal_title.textContent = `Edit Supplier ${res.name}`;
                    }
                })
            })
        });
        var table = new DataTable('#supplier-table', {
            search: {
                return: true,
            },
            scrollY: false,
            processing: true,
            pageLength: 100,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    text: 'Export Excel <i class="fas fa-file-excel" ></i>',
                    className: 'btn btn-success text-capitalize',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    text: 'Save <i class="fas fa-file-pdf"></i>',
                    className: 'btn btn-danger text-capitalize',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'print',
                    text: 'Print <i class="fas fa-print"></i>',
                    className: 'btn btn-primary text-capitalize',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
            ]
        });
        document.querySelector('input[aria-controls="supplier-table"]').placeholder = "Search table";
        $('input[aria-controls="supplier-table"]').on('keyup', function() {
            table.search(this.value).draw();
        });
    </script>
@endsection
