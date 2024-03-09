@extends('app.index')
@section('content')
    <div class="container-fluid mt-4">
        @php
            use Carbon\Carbon;
            $user = auth()->user();
        @endphp
        {{-- <h2 class="h4 text-uppercase">{{ $heading ?? 'Settings' }}</h2> --}}
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="text-start">
                    <h5 class="h5 fw-semibold text-uppercase  text-primary mt-3 ">Profile</h5>
                    <img src="{{ auth()->user()->photo }}" alt="user image" height="200" width="200"
                        class="bg-body-tertiary " />
                    <div class="d-flex justify-content-start">
                        <table class=" table-borderless ">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="me-2 text-capitalize">username</th>
                                    <td class="ms-3 fw-semibold text-uppercase text-success">:
                                        {{ auth()->user()->username }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="me-2 text-capitalize">fullname</th>
                                    <td class="ms-3 fw-semibold text-uppercase text-success">:
                                        {{ auth()->user()->fullname }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="me-2 text-capitalize">gender</th>
                                    <td class="ms-3 fw-semibold text-uppercase text-success">:
                                        {{ auth()->user()->gender === 1 ? 'male' : 'female' }}</td>
                                </tr>
                                <tr>
                                    <th class="me-2 text-capitalize">date of birth</th>
                                    <td class="ms-3 fw-semibold text-uppercase text-success">:
                                        {{ Carbon::parse(auth()->user()->date_of_birth)->format('l, d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th class="me-2 text-capitalize">login time</th>
                                    <td class="ms-3 fw-semibold text-uppercase text-success">:
                                        {{ Carbon::parse(auth()->user()->login_at)->format('H:i:s') }}
                                        ({{ Carbon::parse(auth()->user()->login_at)->diffForHumans() }})
                                    </td>
                                </tr>
                                <tr>
                                    <th class="me-2 text-capitalize text-truncate ">last logout</th>
                                    <td class="ms-3 fw-semibold text-uppercase text-success">:
                                        @if (auth()->user()->logout_at !== null)
                                            {{ Carbon::parse(auth()->user()->logout_at)->format('H:i:s') }}
                                            ({{ Carbon::parse(auth()->user()->logout_at)->diffForHumans() }})
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a type="button" class="btn btn-outline-success mt-3" href="#" data-bs-toggle="modal" data-bs-target="#change-password">change password</a>

                </div>
            </div>
            <div class=" col-md-6 col-sm-6">
                <div class="card bg-none shadow m-2 border-0 " style="max-width: 520px">
                    <h4 class="card-header text-center text-uppercase text-primary  ">update user profile</h4>
                    <div class="card-body">
                        <form id="user-update" method="POST" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            @if ($errors->any())
                                <ul class="list-unstyled d-flex justify-content-center">
                                    <div class="alert alert-danger alert-dismissible">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </div>
                                </ul>
                            @endif
                            <div class="form-outline mb-4">
                                <input type="text" autofocus name="username" id="username" class="form-control"
                                    value="{{ $user->username }}" />
                                <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" autofocus name="fullname" id="fullname" class="form-control"
                                    value="{{ $user->fullname }}" />
                                <label class="form-label" for="fullname">Fullname <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" autofocus name="phone" id="phone" class="form-control"
                                    value="{{ $user->phone }}" />
                                <label class="form-label" for="phone">Phone <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group mb-4">
                                <label class="form-label" for="fullname">Gender <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-select">
                                    @if ($user->gender === 1)
                                        <option value="1" selected>Male</option>
                                        <option value="0">Female</option>
                                    @else
                                        <option value="1">Male</option>
                                        <option value="0" selected>Female</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label class="form-label" for="date_of_birth">Date of Birth <span
                                        class="text-danger">*</span></label>
                                <input required type="date" autofocus name="date_of_birth" id="date_of_birth"
                                    class="form-control" value="{{ $user->date_of_birth }}" />
                            </div>                            
                            <div class="row mb-4">
                                <div class="col-4">
                                    <div class="image-preview-wrapper">
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ $user->photo }}" alt=""
                                                class="image-preview rounded-circle bg-secondary  " height="65"
                                                width="65" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <label for="customerImage">Upload Image</label>
                                    <input type="file" onchange="preview()" name="image"
                                        id="customerImage" accept="image/*" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn text-capitalize btn-primary btn-block">
                                    <span id="loading-icon" class="fas fa-spinner fa-spin"></span>
                                    update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.change_password_modal')
@endsection
@section('script')
    <script>
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
        $('#loading-icon').hide();
        $('#user-update').on('submit', function() {
            $(this).attr('action', '/update-profile');
            return true;
        })
    </script>
@endsection
