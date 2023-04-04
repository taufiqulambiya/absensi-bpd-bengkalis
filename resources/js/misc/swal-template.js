export const swalConfirmator = (title, text, confirmButtonText = 'Ya, hapus', callback) => {
    import("sweetalert2").then(({ default: Swal }) => {
        Swal.fire({
            title,
            text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText,
        }).then((result) => {
            if (result.isConfirmed) {
                callback(Swal);
            }
        });
    });
};
