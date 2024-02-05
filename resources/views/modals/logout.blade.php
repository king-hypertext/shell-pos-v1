<div class="modal fade" id="logout" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="dialog">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-body p-4 text-center">
                <h5 class="mb-0">LOGOUT</h5>
                <p class="mb-0">Are you sure you want to logout?</p>
            </div>
            <form action="/logout" method="post">
                <div class="modal-footer flex-nowrap p-0">
                    @csrf
                    <button type="submit"
                        class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end">
                        <strong>Yes, log me out</strong>
                    </button>
                    <button type="button"
                        class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0"
                        data-bs-dismiss="modal">No, discard
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
