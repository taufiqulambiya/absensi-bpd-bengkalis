<script src="{{ asset('admin-assets/vendors/sweetalert/sweetalert2@11.js') }}"></script>
<div>
    <script>
        function showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                confirmButtonColor: '#007bff',
            })
        }
        function showSuccessAlert(message, callback = () => {}) {
            Swal
            .mixin({
                customClass: {
                    confirmButton: 'btn btn-primary',
                },
                buttonsStyling: false,
            })
            .fire({
                icon: 'success',
                title: 'Sukses',
                text: message,
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }
        function showConfirmDialog(message, callback) {
            Swal.fire({
                title: 'Lanjutkan proses?',
                text: message,
                showCancelButton: true,
                confirmButtonText: 'Lanjut',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#007bff',
                }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }
        // const baseURL = `{{ URL::to('/') }}`;
    </script>
</div>