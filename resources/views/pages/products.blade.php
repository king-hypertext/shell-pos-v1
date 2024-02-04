@extends('app.index')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="container-fluid mt-4">
        <h2 class="h2 text-uppercase">{{ $heading ?? 'All Products' }}</h2>
        <div class="d-flex justify-content-end me-1 mb-2">
            <a target="_blank" href="{{ route('view-file.open-stock') }}" class="btn btn-success mx-2">Open Stock</a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                Add Product
            </a>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger my-2 alert-dismissible ">
                <ul class="list-unstyled d-flex justify-content-center">
                    @foreach ($errors->all() as $error)
                        <li class="lead ">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <style type="text/css">
            input[name="check-box"] {
                cursor: pointer;
                width: 20px;
                height: 20px;
            }
        </style>
        <div class="table-responsive">
            <table class="table table-hover" id="product-table">
                <thead>
                    <tr>
                        <th hidden></th>
                        <th scope="col">#</th>
                        <th scope="col">PRODUCT</th>
                        <th scope="col">IMAGE</th>
                        <th scope="col">PRICE(GHS)</th>
                        <th scope="col" title="available quantity">AVAIL. QTY</th>
                        <th scope="col">SUPPLIED BY</th>
                        <th scope="col" title="category">CAT.</th>
                        <th scope="col">PROD. DATE</th>
                        <th scope="col">EXPIRY DATE</th>
                        <th scope="col">Edit</th>
                        <th>
                            <span class="action-header">Misc</span>
                            <a href="#" role="button" class="link-delete text-danger d-none"
                                onclick="DeleteProdructs(event)">Delete selected
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                        <tr>
                            <td hidden>{{ $product->created_at }}</td>
                            <td scope="row">{{ $index + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                <img src="{{ $product->image }}" alt="product-image" class="table-image" />
                            </td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->supplied_by }}</td>
                            <td>{{ $product->category }}</td>
                            <td>{{ Carbon::parse($product->prod_date)->format('Y-M-d') }}</td>
                            <td>{{ Carbon::parse($product->expiry_date)->format('Y-M-d') }}</td>
                            <td>
                                <button type="button" id="{{ $product->id }}" class="btn_edit btn text-primary my-1"
                                    title="Edit {{ $product->name }}">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            </td>
                            <td>
                                <div class="row justify-content-center align-items-center mb-0 pt-3">
                                    <input type="checkbox" name="check-box" title="Select" id="{{ $product->id }}"
                                        value="{{ $product->id }}" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="modal-edit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="modal-edit" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="dialog">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-body p-2">
                        <div class="row justify-content-center">
                            <div class="divider my-0">
                                <div class="divider-text">
                                    <h5 class="h3 text-capitalize px-5 " id="m-e-title">Edit Product</h5>
                                </div>
                            </div>
                            <form id="form-update-product" class="px-5 py-2" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="product_id">
                                @csrf
                                @method('PUT')
                                <div class="form-group mb-4">
                                    <label class="form-label" for="productName">Product Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input required type="text" onfocus="this.select()" name="product-name"
                                        id="productName" class="form-control" />
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label" for="unitPrice">Unit Price
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input required type="number" onfocus="this.select()" name="price"
                                                id="unitPrice" class="form-control" step=".01" />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label" for="batchNumber">Batch Number</label>
                                            <input type="text" name="batch-number" id="batchNumber"
                                                class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label for="category" class="form-label">Category
                                            <span class="text-danger">*</span></label>
                                        <select required name="category" id="category" class="form-select">
                                            <option value="SHELL">SHELL</option>
                                            <option value="ALLIED">ALLIED</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="supplier" class="form-label">Supplier
                                                <span class="text-danger">*</span></label>
                                            <select required name="supplier" id="supplier" class="form-select">
                                                @php
                                                    use App\Models\Suppliers;
                                                    $suppliers = Suppliers::select('name')->get();
                                                @endphp
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label class="form-label" for="productionDate">Manufacturing Date</label>
                                        <input type="date" max="{{ Date('Y-m-d') }}" name="prod_date"
                                            id="productionDate" class="form-control" />
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="expiryDate">Expiry Date <span
                                                class="text-danger">*</span></label>
                                        <input required type="date" min="{{ Date('Y-m-d') }}" name="expiry_date"
                                            id="expiryDate" class="form-control" />
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        <div class="image-preview-wrapper-s d-flex justify-content-center">
                                            <img src="" alt="" class="image-preview-s"
                                                id="server-preview" style="width: 55px; height: 55px;">
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="mb-4">
                                            <label for="product_image">Product Image</label>
                                            <input type="file" onchange="previewImageFromServer()"
                                                name="product-image" id="product_image" class="form-control"
                                                accept="image/*" />
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Update Product</button>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                        <button type="button" class="btn btn-sm btn-secondary" id="modal-edit-close">Discard</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
        <script>
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
        const showSuccessAlert = Swal.mixin({
            position: 'top-end',
            toast: true,
            timer: 6500,
            showConfirmButton: false,
            timerProgressBar: false,
        });
        var ids = [];
        $('input[name="check-box"]').on('change', function(e) {
            if (e.currentTarget.checked) {
                ids.push(e.currentTarget.value);
                console.log(ids);
            } else {
                ids.pop(e.currentTarget.value)
                console.log(ids);
            }
            if (ids.length != 0) {
                $('.link-delete').removeClass('d-none')
                $('.action-header').addClass('d-none')
                // console.log('not empty');
            } else {
                $('.link-delete').addClass('d-none')
                $('.action-header').removeClass('d-none')
                // console.log('empty');
            }
        });
        window.DeleteProdructs = function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Delete Selected!",
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/products/delete',
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: ids
                        },
                        success: function(res) {
                            console.log(res);
                            if (res.success) {
                                showSuccessAlert.fire({
                                    icon: 'success',
                                    text: res.success,
                                    padding: '10px',
                                    width: 'auto'
                                });
                            }
                            location.reload();
                        }
                    });
                }
            })
        }
        var image_wrapper = $('.image-preview-wrapper-s');
        image_wrapper.show();

        function previewImageFromServer() {
            var img = document.querySelector('img.image-preview-s');
            var input_data = document.getElementById('product_image');
            var file_preview = input_data.files[0];
            const filereader = new FileReader();
            filereader.addEventListener('load', (e) => {
                img.src = e.target.result;
            });
            filereader.readAsDataURL(file_preview);
        }
        $(document).ready(function() {
            $(document).on('click', 'button.btn_edit', function(e) {
                var data_id = e.currentTarget.id;
                $.ajax({
                    url: "/products/" + e.currentTarget.id,
                    success: function(data) {
                        console.log(data);
                        $('input[name="id"]').val(data.id);
                        $('#productName').val(data.name);
                        $('#unitPrice').val(data.price);
                        $('#batchNumber').val(data.batch_number);
                        $('select[name="category"]').val(data.category);
                        $('select[name="supplier"]').val(data.supplied_by);
                        $('#productionDate').val(data.prod_date);
                        $('#expiryDate').val(data.expiry_date);
                        $('img#server-preview')[0].src = data.image;
                    }
                });
                $('#modal-edit').modal('show');
                $('button#modal-edit-close').on('click', function() {
                    $('#modal-edit').modal('hide');
                });
            });
            $('#form-update-product').on('submit', function(e) {
                $(this).attr('action', '/products/' + e.currentTarget[0].value);
                return true;
            })
        });
        window.confirmDelete = function(e) {
            e.preventDefault();
            var form = e.target.offsetParent.form;
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
        }
        var table = new DataTable('#product-table', {
            processing: true,
            search: {
                return: true,
            },
            order: [
                [0, 'desc']
            ],
            pageLength: 200,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    text: 'Export Excel <i class="fas fa-file-excel" ></i>',
                    className: 'btn btn-success text-capitalize',
                    title: 'All Products',
                    filename: 'products',
                    exportOptions: {
                        columns: [0, 1, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    text: 'Save <i class="fas fa-file-pdf"></i>',
                    className: 'btn btn-danger text-capitalize',
                    title: 'All Products',
                    extend: 'pdf',
                    filename: 'products',
                    exportOptions: {
                        columns: [0, 1, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    text: 'Print <i class="fas fa-print"></i>',
                    className: 'btn btn-primary text-capitalize',
                    title: 'All Products',
                    extend: 'print',
                    filename: 'products',
                    exportOptions: {
                        columns: [0, 1, 3, 4, 5, 6, 7, 8]
                    }
                },
            ]
        });
        $('input[aria-controls="product-table"]')[0].placeholder = 'Search table';
        $('input[aria-controls="product-table"]').on('keyup', function() {
            table.search(this.value).draw();
        });
    </script>
@endsection
