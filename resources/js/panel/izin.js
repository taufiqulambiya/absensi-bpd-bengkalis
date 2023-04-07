const warningSwal = (text, confirmed = () => {}) => Swal.fire({
    icon: 'warning',
    title: 'Peringatan',
    text,
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya',
    cancelButtonText: 'Batal'
}).then((result) => {
    if (result.isConfirmed) {
        confirmed();
    }
});

class Izin {
    constructor() {
        this.init();
    }

    init() {
        // confirm delete
        livewire.on('izin:delete', (id) => {
            warningSwal('Apakah anda yakin ingin menghapus izin ini?', () => {
                livewire.emit('procceedDeleteIzin', id);
            });
        });
        // acc izin
        livewire.on('izin:acc', (id) => {
            warningSwal('Apakah anda yakin ingin menerima izin ini?', () => {
                livewire.emit('procceedAccIzin', id);
            });
        });
        // reject izin
        livewire.on('izin:reject', (id) => {
            warningSwal('Apakah anda yakin ingin menolak izin ini?', () => {
                livewire.emit('procceedRejectIzin', id);
            });
        });
    }
}

export default Izin;