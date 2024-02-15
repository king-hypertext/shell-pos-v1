@extends('install.layout')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card px-5 pb-5 border-0 my-5" style="max-width: 520px">
                <form id="user-signup" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <input readonly type="hidden" name="skip" value="skip" />
                    <div class="text-center">
                        {{-- <img src="{{ url('icon.png') }}" style="height: 80px" alt="logo"> --}}
                        <h5 class="h6 text-uppercase text-danger pt-2 text-decoration-underline ">
                            q-pos installation wizard - final setup
                        </h5>
                    </div>
                    <h3 class="h4 text-center text-uppercase  text-primary py-4">
                        upload user photo
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
                    <div class="image-preview-wrapper">
                        <div class="d-flex justify-content-center">
                            <img src="" alt="" class="image-preview rounded-circle bg-black " height="65"
                                width="65" />
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{ $id ?? 1 }}">
                    <div class="mb-4">
                        <label for="customerImage">Upload Image</label>
                        <input required type="file" onchange="preview()" name="image" id="customerImage"
                            accept="image/*" class="form-control" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                            <span id="loading-icon" class="fas fa-spinner fa-spin"></span>
                            finish setup
                        </button>
                    </div>
                    <div class="form-group">
                        <h6 class="h6 fw-semibold text-center mb-0 mt-2 ">or</h6>
                        <hr class="hr mt-0 pt-0">
                        <button type="button" id="skip" class="btn btn-success">Skip</button>
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
        $(document).ready(function() {
            $('#db-configuration-error').hide();
            $('#loading-icon').hide();
            $('#user-signup').on('submit', function(e) {
                $('#loading-icon').show();
                $(this).attr('action', '{{ route('installer.step3') }}')
                return true;
            });
            $('button#skip').on('click', function(e) {
                var form = e.currentTarget.form;
                e.currentTarget.form[3].required = false;
                e.currentTarget.form[3].setAttribute('readonly', true);
                e.currentTarget.form[1].type = 'text';
                e.currentTarget.form[1].readonly = false;
                e.currentTarget.form[1].classList.add('invisible');
                form.submit();
            });
        })
    </script>
@endsection
