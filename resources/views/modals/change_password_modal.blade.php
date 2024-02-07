<div class="modal fade" id="change-password" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="dialog">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-body p-4 text-center">
                <h5 class="mb-0">CHANGE PASSWORD</h5>
            </div>
            <div class="alert alert-danger " id="error-msg"></div>
            <form id="form-change-password" method="post" autocomplete="off">
                <div class="card px-5 border-0 ">
                    @csrf
                    <div class="form-outline mb-4">
                        <input required type="number" autofocus name="secret_code" id="secret_code"
                            class="form-control" />
                        <label class="form-label" for="secret_code">Secret Code <span
                                class="text-danger">*</span></label>
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="password" autofocus name="password" id="password" class="form-control" />
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn text-capitalize btn-primary btn-block">
                            <span id="btn-icon" class="fas fa-spinner fa-spin"></span>
                            update
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary ripple-surface"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const showSuccessAlert = Swal.mixin({
        position: 'top-end',
        toast: true,
        timer: 6500,
        showConfirmButton: false,
        timerProgressBar: false,
    });
    $('#btn-icon').hide();
    $('#error-msg').hide();
    $('#form-change-password').on('submit', function(e) {
        e.preventDefault();
        $('#btn-icon').show();
        console.log(e);
        $.ajax({
            url: '/auth/change-password',
            type: 'POST',
            data: {
                _token: e.currentTarget[0].value,
                secret_code: e.currentTarget[1].value,
                password: e.currentTarget[2].value
            },
            success: function(res) {
                $('#btn-icon').hide();
                console.log(res);
                if (res.success) {
                    showSuccessAlert.fire({
                        icon: 'success',
                        text: res.success,
                        padding: '10px',
                        width: 'auto'
                    });
                    window.location.reload();
                }
            },
            error: function(error) {
                $('#btn-icon').hide();
                console.log(error);
                if (error && error.status == 422) {
                    $('#error-msg').show();
                    $('#error-msg').text(error.responseJSON.message);
                }
            }
        });
        $(this).attr('action', '/update-profile');
        return true;
    })
</script>
