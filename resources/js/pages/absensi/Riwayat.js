import $ from "jquery";
import { get } from "lodash";
import { commonHeaderTemplate } from "../../misc/print-template";
import { getJson } from "../../utils/api.service";

export default class Riwayat {
    constructor() {
        this.state = {};
        this.data = [];
        this.init();
    }

    init() {
        const dataContainer = $(".data-container");
        dataContainer.each((index, item) => {
            const data = $(item).data();
            Object.entries(data).forEach(([key, value]) => {
                this.state[key] = value;
            });
        });

        if (this.state.currentUrl) {
            getJson(this.state.currentUrl).then((data) => {
                console.log(data);
                this.data = data.absensi || [];
            });
        }

        $("#print-all").on("click", () => {
            this.printAll();
        });

        $(".btn-print-detail").on("click", (e) => {
            const id = $(e.currentTarget).data("id");
            this.printDetail(id);
        });
    }

    printAll() {
        const CONTENT_HEADER = [
            // "NIP",
            // "Nama",
            "Tanggal",
            "Jam Masuk",
            "Jam Keluar",
            "Total Jam",
            "Keterangan",
        ].map((item) => {
            return {
                text: item,
                style: "tableHeader",
            };
        });

        /**
         * {
                "id": 69,
                "id_user": 1,
                "id_jam": 3,
                "tanggal": "2022-12-27",
                "waktu_masuk": "00:30:00",
                "lat_masuk": null,
                "long_masuk": null,
                "waktu_keluar": "23:59:00",
                "lat_keluar": null,
                "long_keluar": null,
                "total_jam": 23,
                "created_at": "2022-12-22T18:08:36.000000Z",
                "updated_at": "2022-12-22T18:08:36.000000Z",
                "dok_masuk": "",
                "dok_keluar": "",
                "forgotten": 0,
                "jarak_masuk": null,
                "jarak_keluar": null,
                "lokasi_masuk": null,
                "lokasi_keluar": null,
                "status": "dinas",
                "dinas_id": "e9d5da5a-1a5e-358c-99c5-610192fad294",
                "jam_absen": "00:30 - 23:59 WIB",
                "user": {
                    "id": 1,
                    "nip": "352560269946978858",
                    "password": "$2y$10$N3OBYVkLuYKqmjE.uw2E0O8EEmxDRaaaWMzzdIX21vr3YwXQ26qZK",
                    "nama": "Marzuki",
                    "golongan": "C",
                    "bidang": "1",
                    "jatah_cuti": 0,
                    "tgl_lahir": "1999-03-14",
                    "jk": "Laki-laki",
                    "alamat": "alamat pegawai marzuki",
                    "no_telp": "082286062083",
                    "gambar": "v1gs1rX68dVmuC26Y2Ccap7n7vNAZoAEaXPmOGxt.png",
                    "level": "pegawai",
                    "remember_token": null,
                    "created_at": null,
                    "updated_at": "2022-12-22T16:49:53.000000Z",
                    "jabatan": "Staff"
                },
                "shift": {
                    "id": 3,
                    "mulai": "00:30:00",
                    "selesai": "23:59:00",
                    "status": "aktif",
                    "created_at": "2022-08-20T09:57:56.000000Z",
                    "updated_at": "2022-12-14T19:51:20.000000Z",
                    "keterangan": "jam kerja regular",
                    "days": "senin, selasa, rabu, kamis"
                }
            }
         */
        const CONTENT_BODY = this.data.map((item) => {
            const {
                // user: { nip, nama },
                formatted_tanggal,
                formatted_waktu_masuk,
                formatted_waktu_keluar,
                total_jam,
                status,
            } = item;
            return [
                // nip,
                // nama,
                formatted_tanggal,
                formatted_waktu_masuk,
                formatted_waktu_keluar,
                total_jam,
                status,
            ];
        });

        const username = get(this.data, "[0].user.nama", "Pegawai");
        const dd = {
            pageSize: "A4",
            pageMargins: [40, 120, 40, 60],
            header: commonHeaderTemplate(`Laporan Absensi - ${username}`),
            content: {
                stack: [
                    {
                        // user info
                        columns: [
                            // vertical span 3 rows line
                            {
                                canvas: [
                                    {
                                        type: "line",
                                        x1: 0,
                                        y1: 0,
                                        x2: 0,
                                        y2: 75,
                                        lineWidth: 1,
                                    },
                                ],
                                width: 10,
                            },
                            {
                                stack: [
                                    {
                                        text: `Nama: ${username}`,
                                        style: "userInfo",
                                    },
                                    {
                                        text: `NIP: ${get(
                                            this.data,
                                            "[0].user.nip",
                                            "NIP"
                                        )}`,
                                        style: "userInfo",
                                    },
                                    {
                                        text: `Jabatan: ${get(
                                            this.data,
                                            "[0].user.jabatan",
                                            "Jabatan"
                                        )} - ${get(
                                            this.data,
                                            "[0].user.bidangs.nama",
                                            ""
                                        )}`,
                                        style: "userInfo",
                                    },
                                ],
                            },
                        ],
                        margin: [0, 0, 0, 15],
                    },
                    {
                        style: "tableExample",
                        table: {
                            headerRows: 1,
                            widths: Array(5).fill(95),
                            heights: [20, 20, 20, 20, 20],
                            body: [CONTENT_HEADER, ...CONTENT_BODY],
                        },
                    },
                ],
            },
            styles: {
                tableExample: {
                    margin: [0, 5, 0, 15],
                },
                tableHeader: {
                    bold: true,
                    fontSize: 13,
                    color: "black",
                },
                userInfo: {
                    fontSize: 12,
                    margin: [0, 5, 0, 5],
                },
            },
        };

        pdfMake.createPdf(dd).open();
    }

    printDetail(id) {
        const found = this.data.find((item) => item.id === id);
        console.log(found);
        /** response
         * {
    "id": 181,
    "id_user": 1,
    "id_jam": 3,
    "tanggal": "2023-03-13",
    "waktu_masuk": "19:42:19",
    "lat_masuk": 0.459047,
    "long_masuk": 101.3957297,
    "waktu_keluar": "19:42:32",
    "lat_keluar": 0.459047,
    "long_keluar": 101.3957297,
    "total_jam": "0 menit",
    "created_at": "2023-03-13T12:42:19.000000Z",
    "updated_at": "2023-03-13T12:42:32.000000Z",
    "dok_masuk": "f28b026b-bff2-3c46-b589-837f14b555da.png",
    "dok_keluar": "3bc6a9ce-b71c-36b3-a10e-0e0f2da5baa3.png",
    "forgotten": 0,
    "jarak_masuk": "38.86 meter",
    "jarak_keluar": "38.86 meter",
    "lokasi_masuk": "Gang Lele Jumbo, Kelurahan Sidomulyo Barat, Kabupaten Kampar, Riau, 23987, Indonesia",
    "lokasi_keluar": "Gang Lele Jumbo, Kelurahan Sidomulyo Barat, Kabupaten Kampar, Riau, 23987, Indonesia",
    "status": "hadir",
    "dinas_id": null,
    "formatted_shift": "00:30 - 23:59 WIB",
    "formatted_waktu_masuk": "19:42 WIB",
    "formatted_waktu_keluar": "19:42 WIB",
    "formatted_tanggal": "13/03/2023",
    "user": {
        "id": 1,
        "nip": "352560269946978858",
        "password": "$2y$10$N3OBYVkLuYKqmjE.uw2E0O8EEmxDRaaaWMzzdIX21vr3YwXQ26qZK",
        "nama": "Marzuki",
        "golongan": "C",
        "bidang": "1",
        "jatah_cuti": 0,
        "tgl_lahir": "1999-03-14",
        "jk": "Laki-laki",
        "alamat": "alamat pegawai marzuki",
        "no_telp": "082286062083",
        "gambar": "v1gs1rX68dVmuC26Y2Ccap7n7vNAZoAEaXPmOGxt.png",
        "level": "pegawai",
        "remember_token": null,
        "created_at": null,
        "updated_at": "2022-12-22T16:49:53.000000Z",
        "jabatan": "Staff",
        "bidangs": {
            "id": 1,
            "created_at": "2022-09-28T20:29:44.000000Z",
            "updated_at": "2022-12-22T16:50:49.000000Z",
            "nama": "Teknologi Informasi"
        }
    },
    "shift": {
        "id": 3,
        "mulai": "00:30:00",
        "selesai": "23:59:00",
        "status": "aktif",
        "created_at": "2022-08-20T09:57:56.000000Z",
        "updated_at": "2022-12-14T19:51:20.000000Z",
        "keterangan": "jam kerja regular",
        "days": "senin, selasa, rabu, kamis",
        "deleted_at": null
    }
}
         */

        const USER = [
            [ { text: "Detail Pegawai", style: "tableHeader", colSpan: 2, alignment: "center", margin: [0, 15] }, {} ],
            ["Nama", get(found, "user.nama", "")],
            ["NIP", get(found, "user.nip", "")],
            ["Jabatan", get(found, "user.jabatan", "")],
            ["Bidang", get(found, "user.bidangs.nama", "")],
        ];

        const ABSEN = [
            [ { text: "Detail Absensi", style: "tableHeader", colSpan: 2, alignment: "center", margin: [0 ,15] }, {} ],
            ["Tanggal", get(found, "formatted_tanggal", "")],
            ["Waktu Masuk", get(found, "formatted_waktu_masuk", "")],
            ["Waktu Keluar", get(found, "formatted_waktu_keluar", "")],
            ["Shift", get(found, "formatted_shift", "")],
            ["Total Jam", get(found, "total_jam", "")],
            ["Lokasi Masuk", get(found, "lokasi_masuk", "")],
            ["Lokasi Keluar", get(found, "lokasi_keluar", "")],
            ["Jarak Masuk", get(found, "jarak_masuk", "")],
            ["Jarak Keluar", get(found, "jarak_keluar", "")],
            ["Status", get(found, "status", "")],
        ];

        const dd = {
            content: [
                {
                    text: "Detail Absensi Pegawai",
                    style: "header",
                },
                {
                    style: "tableExample",
                    table: {
                        headerRows: 1,
                        widths: [120, "*"],
                        heights: [20, 20, 20, 20, 20],
                        body: [...USER, ...ABSEN],
                    },
                },
            ],
            styles: {
                header: {
                    fontSize: 18,
                    bold: true,
                    alignment: "center",
                    margin: [0, 5, 0, 15],
                },
                tableExample: {
                    margin: [0, 5, 0, 15],
                },
                tableHeader: {
                    bold: true,
                    fontSize: 13,
                    color: "black",
                },
            },
        };

        pdfMake.createPdf(dd).open();
    }
}
