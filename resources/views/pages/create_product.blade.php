@extends('app.index')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="row">
        <h2 class="h2 text-uppercase">add a new product</h2>

        <div class="col-lg-6">
            <div class="card rounded-3 shadow">
                <div class="modal-body p-2">
                    <div class="row justify-content-center">
                        <div class="divider my-0">
                            <div class="divider-text">
                                <h5 class="h3 text-capitalize">Add New Product</h5>
                            </div>
                        </div>
                        @if ($errors->any())
                            <ul class="list-unstyled d-flex justify-content-center">
                                <div class="alert alert-danger alert-dismissible">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </div>
                            </ul>
                        @endif
                        <form id="create_product" action="{{ route('products.store') }}" class="px-5 py-2" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-outline mb-4">
                                <input required type="text" value="{{ @old('product-name') }}" name="product-name"
                                    id="productName" class="form-control" />
                                <label class="form-label" for="productName">Product Name
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="form-outline">
                                        <input required type="number" value="{{ @old('unit-price') }}" step=".01"
                                            placeholder="0.00" name="price" id="unitPrice" class="form-control" />
                                        <label class="form-label" for="unitPrice">Unit Price
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-outline">
                                        <input type="text" name="batch-number" value="{{ @old('batch-number') }}"
                                            id="batchNumber" class="form-control" />
                                        <label class="form-label" for="batchNumber">Batch Number</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="form-outline">
                                        <label for="supplier" class="form-label">Supplier
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select required name="supplier" id="supplier" class="form-select">
                                            <option selected value="">Select Supplier</option>
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
                                <div class="col-6">
                                    <label for="category">Category</label>
                                    <input type="text" name="category" id="category" class="form-control mt-2"
                                        readonly />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="form-label" for="productionDate">Manufacturing Date</label>
                                    <input type="date" max="{{ Date('Y-m-d') }}"
                                        name="prod_date" id="productionDate" class="form-control" />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="expiryDate">Expiry Date
                                        {{-- <span class="text-danger">*</span> --}}
                                    </label>
                                    <input type="date" min="{{ Date('Y-m-d') }}"
                                        name="expiry_date" id="expiryDate" class="form-control" />
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="image-preview-wrapper">
                                        <img src="" alt="" class="image-preview"
                                            style="width: 55px; height: 55px;">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mb-4">
                                        <label for="productImage">Product Image</label>
                                        <input type="file" onchange="previewImage()" name="product-image"
                                            id="productImage" class="form-control" accept="image/*" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Add Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="container">
                <h4 class="h4 text-uppercase">All Products List</h4>
                <table class="table table-sm table-bordered" id="products-list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th scope="col">Product</th>
                            <th scope="col" role="cell">Quantity</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ Carbon::parse($product->created_at)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
        var cat = $('input[name="category"]');
        $('select[name="supplier"]').on('change', function(e) {
            let name = e.currentTarget.value;
            if (!name) {
                return 0;
            } else {
                $.ajax({
                    url: '/supplier-category/' + e.currentTarget.value,
                    success: (res) => {
                        console.log(res);
                        cat.val(res[0].category);
                    },
                    error: (err) => {
                        console.log(err);
                    }
                })
            }
        });
        var table = new DataTable('#products-list', {
            processing: true,
            search: {
                return: true,
            },
            pageLength: 15,
        });
        $('input[aria-controls="products-list"]')[0].placeholder = "Search table...";
        $('input[aria-controls="products-list"]').on('keyup', function() {
            table.search(this.value).draw();
        });
        var img_wrapper = $('.image-preview-wrapper');
        img_wrapper.hide();

        function previewImage() {
            var image = document.querySelector('img.image-preview');
            var input = document.getElementById('productImage');
            var file = input.files[0];
            const filereader = new FileReader();
            filereader.addEventListener('load', (e) => {
                image.src = e.target.result;
                img_wrapper.show();
            });
            filereader.readAsDataURL(file);
        }
    </script>
@endsection
