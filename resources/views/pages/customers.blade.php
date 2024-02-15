@extends('app.index')
@section('content')
    <div class="container-fluid mt-4 ">
        <h2 class="h2 text-uppercase">All Workers</h2>
        <div class="d-flex justify-content-end me-1 mb-2">
            <a class="btn btn-success me-2 " href="{{ route('customer.index') }}" role="button">Create Order</a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-addCustomer">
                Add Worker
            </button>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="d-flex justify-content-center">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="table-responsive">
            <style>
                .table {
                    padding: 0;
                }

                td>form {
                    margin-bottom: 0 !important;
                }

                td#col_id {
                    font-weight: bold;
                }
            </style>
            <table class="table table-hover" id="customer-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">WORKER</th>
                        <th scope="col">IMAGE</th>
                        <th scope="col">CONTACT</th>
                        <th scope="col">ADDRESS</th>
                        <th scope="col">GENDER</th>
                        <th scope="col" title="Date of Birth">DOB</th>
                        <th scope="col">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $index => $customer)
                        <tr class="text-uppercase ">
                            <td id="col_id" scope="col">{{ $index + 1 }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>
                                <img class="table-image" src="{{ $customer->image }}" alt="product-image" />
                            </td>
                            <td>{{ $customer->contact }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->gender }}</td>
                            <td>{{ $customer->date_of_birth }}</td>
                            <td>
                                <form action='{{ route('customers.destroy', ["$customer->id"]) }}' method="post">
                                    @method('DELETE')
                                    <button type="button" id="{{ $customer->id }}"
                                        class="btn_edit btn text-primary text-uppercase my-1"
                                        title="Edit {{ $customer->name }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    @csrf
                                    <button type="button" onclick="confirmDelete(event)"
                                        class="btn text-danger text-uppercase my-1 outline-0"
                                        title="delete {{ $customer->name }}">
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
    <div class="modal fade" id="modal-edit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="modal-edit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-2">
                    <div class="row justify-content-center">
                        <div class="divider my-0">
                            <div class="divider-text">
                                <h5 class="h3 text-capitalize px-5 " id="modal-title">Edit Customer</h5>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <img id="customer-image" src="" alt="Product Image"
                                class="rounded-circle product-image bg-light d-none" />
                        </div>
                        <form class="px-5 py-2" id="form_edit_worker" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="customer_id">
                            <div class="form-outline mb-4">
                                <input required type="text" name="customer-name" id="customertName"
                                    class="form-control" />
                                <label class="form-label" for="customertName">Worker Name</label>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="form-label" for="dob">Date of Birth</label>
                                    <input type="date" max="{{ Date('Y-m-d') }}" name="dob" id="dob"
                                        class="form-control" />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select required name="gender" id="cust-gender" class="form-select">
                                        <option disabled>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-outline mb-4">
                                <input required type="text" onfocus="this.type='number'" name="contact"
                                    id="customerContact" class="form-control" />
                                <label class="form-label" for="customerContact">Contact</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" name="address" id="customerAddress" class="form-control" />
                                <label class="form-label" for="customerAddress">Address</label>
                            </div>
                            <div class="image-preview-wrapper-s">
                                <img src="" alt="" class="image-preview-s" id="cust-image"
                                    style="width: 55px; height: 55px;">
                            </div>
                            <div class="mb-4">
                                <label for="customerImage">Worker Image</label>
                                <input type="file" onchange="previewImageFromServer()" name="customer-image"
                                    accept="image/*" id="customer_image" class="form-control" />
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update Worker</button>
                        </form>
                    </div>
                </div>
                <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary" id="modal-edit-close">Discard</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-addCustomer" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="modal-addCustomer" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-2">
                    <div class="row justify-content-center">
                        <div class="divider my-0">
                            <div class="divider-text">
                                <h5 class="h3 text-capitalize px-5 ">Add New Worker</h5>
                            </div>
                        </div>
                        <form autocomplete="off" class="px-5 py-2" action="{{ route('customers.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-outline mb-4">
                                <input required type="text" name="customer-name" id="cusName"
                                    placeholder="Enter worker name" class="form-control autofocus" autofocus />
                                <label class="form-label" for="cusName">Worker Name</label>
                            </div>
                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="form-label" for="birth-date">Date of Birth</label>
                                    <input type="date" max="{{ Date('Y-m-d') }}" name="dob" id="birth-date"
                                        class="form-control" />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select required name="gender" id="worker-gender" class="form-select">
                                        <option selected disabled>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-outline mb-4">
                                <input required type="number" name="contact" id="cusContact" class="form-control" />
                                <label class="form-label" for="customerContact">Contact</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" name="address" id="cusAddress" class="form-control" />
                                <label class="form-label" for="cusAddress">Address</label>
                            </div>
                            <div class="image-preview-wrapper">
                                <img src="" alt="" class="image-preview"
                                    style="width: 55px; height: 55px;">
                            </div>
                            <div class="mb-4">
                                <label for="customerImage">Worker Image</label>
                                <input required type="file" onchange="preview()" name="customer-image"
                                    id="customerImage" accept="image/*" class="form-control" />
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Add Customer</button>
                        </form>
                    </div>
                </div>
                <div class="d-flex my-0 pb-2 pe-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Discard</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var img_wrapper = $('.image-preview-wrapper');
        img_wrapper.hide();

        function preview() {
            var image = document.querySelector('img.image-preview');
            var input = $('input[type="file"]#customerImage')[0];
            var file = input.files[0];
            const filereader = new FileReader();
            filereader.addEventListener('load', (e) => {
                image.src = e.target.result;
                img_wrapper.show();
            });
            filereader.readAsDataURL(file);
        }

        function previewImageFromServer() {
            var img = document.querySelector('img.image-preview-s');
            var input_data = document.getElementById('customer_image');
            var file_preview = input_data.files[0];
            const filereader = new FileReader();
            filereader.addEventListener('load', (e) => {
                img.src = e.target.result;
            });
            filereader.readAsDataURL(file_preview);
        }
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
        $(document).ready(function() {
            $("#form_edit_worker").submit(function(event) {
                $(this).attr("action", "/customers/" + event.currentTarget[1].value);
                return true;
            });

            $(document).on('click', 'button.btn_edit', function(e) {
                var customer_id = e.currentTarget.id;
                var dob = $('input#dob'),
                    name = $('input#customertName'),
                    gender = $('select#cust-gender'),
                    contact = $('input#customerContact'),
                    address = $('input#customerAddress');
                $.ajax({
                    url: "/customers/" + customer_id,
                    success: function(data) {
                        console.log(data);
                        $('#customer_id').val(data.id);
                        name.val(data.name);
                        dob.val(data.date_of_birth);
                        gender.val(data.gender);
                        contact.val(data.contact);
                        address.val(data.address);
                        $('img#cust-image')[0].src = data.image;
                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
                $('#modal-edit').modal('show');
                $('button#modal-edit-close').on('click', function() {
                    $('#modal-edit').modal('hide');
                });
            })
        });
    </script>

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
        var table = new DataTable('#customer-table', {
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
                        columns: [0, 1, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdf',
                    text: 'Save <i class="fas fa-file-pdf"></i>',
                    className: 'btn btn-danger text-capitalize',
                    exportOptions: {
                        columns: [0, 1, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    text: 'Print <i class="fas fa-print"></i>',
                    className: 'btn btn-primary text-capitalize',
                    exportOptions: {
                        columns: [0, 1, 3, 4, 5, 6]
                    }
                },
            ]
        });
        document.querySelector('input[aria-controls="customer-table"]').placeholder = "Search table";
        $('input[aria-controls="customer-table"]').on('keyup', function() {
            table.search(this.value).draw();
        });
    </script>
@endsection
