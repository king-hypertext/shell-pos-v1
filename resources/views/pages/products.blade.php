@extends('app.index')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="container-fluid mt-4">
        <h2 class="h2 text-uppercase">All Products</h2>
        <div class="d-flex justify-content-end me-1 mb-2">
            <a target="_blank" href="{{ route('generate-invoice.index') }}" class="btn btn-success mx-2">Open Stock</a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                Add Product
            </a>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger my-2">
                <ul class="list-unstyled d-flex justify-content-center">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover" id="product-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">PRODUCT</th>
                        <th scope="col">IMAGE</th>
                        <th scope="col">PRICE(GHS)</th>
                        <th scope="col" title="available quantity">AVAIL. QTY</th>
                        <th scope="col">SUPPLIED BY</th>
                        <th scope="col" title="category">CAT.</th>
                        <th scope="col">PROD. DATE</th>
                        <th scope="col">EXPIRY DATE</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td scope="row">{{ $product->id }}</td>
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
                                <form action='{{ route('products.destroy', ["$product->id"]) }}' method="post">
                                    @method('DELETE')
                                    <button type="button" id="{{ $product->id }}" class="btn_edit btn text-primary my-1"
                                        title="Edit {{ $product->name }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}" readonly>
                                    <button onclick="confirmDelete(event)" type="button" class="btn text-danger my-1"
                                        title="delete {{ $product->name }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- modal edit --}}
        <div class="modal fade" id="modal-edit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="modal-addProduct" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="dialog">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-body p-2">
                        <div class="row justify-content-center">
                            <div class="divider my-0">
                                <div class="divider-text">
                                    <h5 class="h3 text-capitalize" id="m-e-title"></h5>
                                </div>
                            </div>
                            <form class="px-5 py-2" action="#" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="product-id">
                                <div class="form-outline mb-4">
                                    <input required type="text" name="edit-product-name" id="productName"
                                        class="form-control" />
                                    <label class="form-label" for="productName">Product Name</label>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <div class="form-outline">
                                            <input required type="number" name="edit-unit-price" id="unitPrice"
                                                class="form-control" step=".01" />
                                            <label class="form-label" for="unitPrice">Unit Price</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-outline">
                                            <input type="text" name="edit-batch-number" id="batchNumber"
                                                class="form-control" />
                                            <label class="form-label" for="batchNumber">Batch Number</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-outline mb-4">
                                    <label for="supplier" class="form-label">Supplier</label>
                                    <select required name="edit-supplier" id="supplier" class="form-select">
                                        @php
                                            use App\Models\Suppliers;
                                            $suppliers = Suppliers::select('name')->get();
                                        @endphp
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label class="form-label" for="productionDate">Manufacturing Date</label>
                                        <input type="date" max="{{ Date('Y-m-d') }}" name="edit-prod-date"
                                            id="productionDate" class="form-control" />
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="expiryDate">Expiry Date</label>
                                        <input required type="date" min="{{ Date('Y-m-d') }}" name="edit-expiry-date"
                                            id="expiryDate" class="form-control" />
                                    </div>
                                </div>
                                <div class="image-preview-wrapper-s">
                                    <img src="" alt="" class="image-preview-s" id="server-preview"
                                        style="width: 55px; height: 55px;">
                                </div>
                                <div class="mb-4">
                                    <label for="product_image">Product Image</label>
                                    <input type="file" onchange="previewImageFromServer()" name="edit-product-image"
                                        id="product_image" class="form-control" accept="image/*" />
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
                $.ajax({
                    url: "/product/edit/" + e.target.id,
                    success: function(data) {
                        console.log(data);
                        $('input#product-id').val(data.id).addClass('active');
                        $('#productName').val(data.name).addClass('active');
                        $('#unitPrice').val(data.price).addClass('active');
                        $('#batchNumber').val(data.batch_number).addClass('active');
                        $('select[name="edit-supplier"]').val(data.supplier);
                        $('#productionDate').val(data.prod_date).addClass('active');
                        $('#expiryDate').val(data.expiry_date).addClass('active');
                        $('img#server-preview')[0].src = "/assets/images/products/" + data
                            .image;
                        $('h5#m-e-title')[0].textContent = `Edit Product ${data.name}`;
                    }
                });
                $('#modal-edit').modal('show');
                $('button#modal-edit-close').on('click', function() {
                    $('#modal-edit').modal('hide');
                });
            });

        });
        window.confirmDelete = function(e) {
            e.preventDefault();
            var form = e.target.form;
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
