import "./bootstrap";

// import AbsensiPegawai from "./pages/absensi/AbsensiPegawai";
// import ListBulanan from "./pages/absensi/ListBulanan";
// import ListHarian from "./pages/absensi/ListHarian";
// import Riwayat from "./pages/absensi/Riwayat";
// import Auth from "./pages/auth/Auth";
// import Cuti from "./pages/cuti/Cuti";
// import IzinAll from "./pages/izin/IzinAll";
// import JamKerja from "./pages/jam_kerja/JamKerja";
// import Report from "./pages/report/Report";
import Swal from "sweetalert2";

document.addEventListener("livewire:load", function () {
    const lw = window.livewire;

    lw.on("success", (message) => {
        Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: message,
        });
    });
    lw.on("error", (message) => {
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: message,
        });
    });
    lw.on("successHtml", (message) => {
        Swal.fire({
            icon: "success",
            title: "Berhasil",
            html: message,
        });
    });
    lw.on("errorHtml", (message) => {
        Swal.fire({
            icon: "error",
            title: "Gagal",
            html: message,
        });
    });

    const initDataTable = () => {
        $(".table").DataTable({
            responsive: true,
            autoWidth: false,
            language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json" },
        });
    };

    const path = window.location.pathname;
    import(`.${path}`)
        .then((module) => {
            new module.default();
        })
        .catch((err) => {
            console.log("module not found");
            console.log(err);
        });
    
    initDataTable();
});
