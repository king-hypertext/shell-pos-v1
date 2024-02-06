@extends('install.layout')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card px-5 pb-5 border-0 my-5" style="max-width: 520px">
                <form id="user-signup" method="POST" autocomplete="off">
                    @csrf
                    <div class="text-center">
                        {{-- <img src="{{ url('icon.png') }}" style="height: 80px" alt="logo"> --}}
                        <h5 class="h6 text-uppercase text-danger pt-2 text-decoration-underline ">
                            q-pos installation wizard -Step 2
                        </h5>
                    </div>
                    <h3 class="h4 text-center text-uppercase  text-primary py-4">
                        user credentials
                    </h3>
                    @if ($errors->any())
                        <ul class="list-unstyled d-flex justify-content-center">
                            <div class="alert alert-danger alert-dismissible">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        </ul>
                    @endif
                    <div class="h6 alert alert-danger alert-dismissible text-danger text-center"
                        id="db-configuration-error"></div>
                    <div class="form-outline mb-4">
                        <input required type="text" autofocus name="username" id="username"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="text" autofocus name="fullname" id="fullname"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="fullname">Fullname <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="text" autofocus name="phone" id="phone"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="phone">Phone Number <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label" for="fullname">Gender <span class="text-danger">*</span></label>
                        <select required name="gender" id="gender" class="form-select">
                            <option value="" selected>Select Gender</option>
                            <option value="1">Male</option>
                            <option value="0">Female</option>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label" for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                        <input required type="date" autofocus name="date_of_birth" id="date_of_birth"
                            class="form-control" />
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="text" autofocus name="secret_code" id="secret_code"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="secret_code">Secret Code <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="password" autofocus name="password" id="password"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                            <span id="loading-icon" class="fas fa-spinner fa-spin"></span>
                            Proceed to Final Step
                        </button>
                    </div>
                </form>
                <div class="form-text">
                    For Installation support?
                    <a target="_blank"
                        href="https://api.whatsapp.com/send/?phone=%2B233543093942&text=Hello,+Please+I+need+support+in+installing+the+Q-POS+software&type=phone_number"
                        title="Click for Support" class="btn-link">Chat</a> or <a href="tel:+233549289243">Call</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#db-configuration-error').hide();
            $('#loading-icon').hide();
            $('#user-signup').on('submit', function(e) {
                $('#loading-icon').show();
                $(this).attr('action', '{{ route('installer.step2') }}')
                return true;
            })
        })
    </script>
@endsection
