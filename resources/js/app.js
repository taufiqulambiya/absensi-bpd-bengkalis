import "./bootstrap";

import $ from "jquery";
// import AbsensiPegawai from "./pages/absensi/AbsensiPegawai";
// import ListBulanan from "./pages/absensi/ListBulanan";
// import ListHarian from "./pages/absensi/ListHarian";
import Riwayat from "./pages/absensi/Riwayat";
import Auth from "./pages/auth/Auth";
import Cuti from "./pages/cuti/Cuti";
import IzinAll from "./pages/izin/IzinAll";
import JamKerja from "./pages/jam_kerja/JamKerja";
import Report from "./pages/report/Report";

// call the constructor by specific route
const switchClass = [
    {
        path: "",
        pathRegex: /^\/$/g,
        exact: true,
        class: Auth,
    },
    {
        path: "auth",
        pathRegex: /auth/g,
        class: Auth,
    },
    // {
    //     path: "/panel/absensi",
    //     pathRegex: /panel\/absensi/g,
    //     exact: true,
    //     class: AbsensiPegawai,
    //     adminClass: ListHarian,
    // },
    // {
    //     path: "panel/absensi?view=harian",
    //     pathRegex: /panel\/absensi\?view=harian/g,
    //     class: ListHarian,
    // },
    // {
    //     path: "panel/absensi?view=bulanan",
    //     pathRegex: /panel\/absensi\?view=bulanan/g,
    //     class: ListBulanan,
    // },
    {
        path: "panel/absensi/riwayat",
        pathRegex: /panel\/absensi\/riwayat/g,
        class: Riwayat,
    },
    {
        path: "panel/jam_kerja",
        pathRegex: /panel\/jam_kerja/g,
        class: JamKerja,
    },
    {
        path: "panel/izin",
        pathRegex: /panel\/izin/g,
        class: IzinAll,
    },
    {
        path: "panel/cuti",
        pathRegex: /panel\/cuti/g,
        class: Cuti,
    },
    // REPORT
    {
        path: "panel/report",
        pathRegex: /panel\/report/g,
        class: Report,
    },
];

async function initStarterData() {
    const dataContainer = $(".data-container");
    const toArray = dataContainer.toArray();
    toArray.forEach(async (item) => {
        const data = $(item).data();
        const { successFlashdata, errorFlashdata, errors } = data;
        const Swal = await import("sweetalert2");
        if (successFlashdata) {
            Swal.default.fire({
                icon: "success",
                title: "Berhasil",
                text: successFlashdata,
            });
        } else if (errorFlashdata) {
            Swal.default.fire({
                icon: "error",
                title: "Gagal",
                text: errorFlashdata,
            });
        } else if (errors) {
            errors.forEach((item) => {
                Swal.default.fire({
                    icon: "error",
                    title: "Gagal",
                    text: item,
                });
            });
        }
    });
}

function initApp() {
    initStarterData();

    const currentPath = window.location.pathname + window.location.search;
    const currentClass = switchClass.find((item) => {
        if (item.exact) {
            return item.path === currentPath;
        }
        return item.pathRegex.test(currentPath);
    });

    // console.log(currentPath, currentClass);
    if (currentClass) {
        const roleEl = $("[data-role]");
        const role = roleEl.data("role");

        console.log(role, currentClass);
        if (role === "admin" && currentClass.adminClass) {
            new currentClass.adminClass();
        } else {
            new currentClass.class();
        }
    }
}

initApp();
