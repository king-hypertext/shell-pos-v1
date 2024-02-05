@extends('install.layout')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card px-5 pb-5 border-0 my-5" style="max-width: 520px">
                <form id="database-configuration" method="POST" autocomplete="off">
                    @csrf
                    <div class="text-center">
                        {{-- <img src="{{ url('icon.png') }}" style="height: 80px" alt="logo"> --}}
                        <h5 class="h6 text-uppercase text-danger pt-2 text-decoration-underline ">
                            q-pos installation wizard -Step 1
                        </h5>
                    </div>
                    <h3 class="h4 text-center text-uppercase  text-primary py-4">
                        app & database configuration
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
                        <input required type="url" autofocus name="app_url" id="app_url"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="app_url">App URL <span class="text-danger">*</span> </label>
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="text" autofocus name="db_name" id="db_name"
                            class="form-control form-control-lg" placeholder="Enter the name of the database" />
                        <label class="form-label" for="db_name">DB Name <span class="text-danger">*</span> </label>
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="text" autofocus name="db_username" id="db_username"
                            class="form-control form-control-lg" value="root"
                            placeholder="Enter the name of the database username" />
                        <label class="form-label" for="db_username">DB Username</label>
                    </div>
                    <div class="form-outline mb-4">
                        <input type="password" name="db_password" id="db_password" class="form-control form-control-lg" />
                        <label class="form-label" for="db_password">DB Password</label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                            <span id="loading-icon" class="fas fa-spinner fa-spin"></span>
                            Proceed to Next Step
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
            $('#database-configuration').on('submit', function(e) {
                e.preventDefault();
                console.log(e);
                $('#loading-icon').show();
                var _token = e.currentTarget[0].value,
                    app_url = e.currentTarget[1].value,
                    db_name = e.currentTarget[2].value,
                    db_username = e.currentTarget[3].value,
                    db_password = e.currentTarget[4].value;
                $.ajax({
                    url: '/install/step-1',
                    type: 'POST',
                    data: {
                        _token: _token,
                        app_url: app_url,
                        db_name: db_name,
                        db_username: db_username,
                        db_password: db_password
                    },
                    success: function(res) {
                        console.log(res);
                        $('#loading-icon').hide();
                        if (res.next) {
                            window.open(res.next, "_self");
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        if (error) {
                            $('#loading-icon').hide();
                            $('#db-configuration-error').show();
                            $('#db-configuration-error').
                            text(error.responseJSON.message +
                                "\n: Unknown Database \"" + db_name + "\"")
                        }
                    }
                });
            })
        })
    </script>
@endsection
