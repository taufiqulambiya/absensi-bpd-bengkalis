import $ from "jquery";
import { get } from "lodash";
import moment from "moment";
import "moment/locale/id";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
import Swal from "sweetalert2";
import { commonHeaderTemplate } from "../../misc/print-template";
pdfMake.addVirtualFileSystem(pdfFonts);

// moment set locale to id
moment.locale("id");

export default class ListHarian {
    constructor() {
        const viewSearchParam = new URLSearchParams(window.location.search).get(
            "view"
        );
        const isHarian = viewSearchParam === "harian";
        const noParam = !viewSearchParam;

        this.data = [];
        this.state = {};

        if (isHarian || noParam) {
            this.init();
        }
    }

    init() {
        console.log("init");
        $('.data-container').each((_, el) => {
            const data = $(el).data();
            Object.entries(data).forEach(([key, value]) => {
                this.state[key] = value;
            });
        });

        this.fetchData();

        const printAllDataEl = $("#print-all-harian");
        printAllDataEl.on("click", () => {
            this.printAllData();
        });

        const printPerIdEl = $(".btn-print-item");
        printPerIdEl.on("click", (e) => {
            const id = e.target.dataset.id;
            console.log(id);
            this.printPerId(id);
        });
    }

    async fetchData() {
        const { currentUrl } = this.state;
        const search = new URLSearchParams(window.location.search);
        const allSearchParam = search.toString();
        // const url = `${currentUrl}?mode=json`;
        const url = `${currentUrl}?mode=json&${allSearchParam}`;
        const response = await $.getJSON(url);
        console.log(response.absensi);
        this.data = response.absensi || [];
    }

    getPrintTitle() {
        const tanggalSearchParam = new URLSearchParams(
            window.location.search
        ).get("tgl");
        if (tanggalSearchParam) {
            const date = moment(tanggalSearchParam, "YYYY-MM-DD");
            const title = `Laporan Absensi Harian Tanggal - ${date.format(
                "DD MMMM YYYY"
            )}`;
            return title;
        }
        return "Laporan Absensi Harian";
    }

    printAllData() {
        const mapData = this.data.map((item) => {
            const absensi = item.absensi[0] || {};
            const obj = {
                nip: item.nip,
                nama: item.nama,
                tanggal: absensi.formatted_tanggal,
                waktu_masuk: absensi.formatted_waktu_masuk,
                waktu_keluar: absensi.formatted_waktu_keluar,
                jam_absen: absensi.formatted_shift,
                total_jam: absensi.total_jam,
            };
            const formatObj = Object.values(obj).map((item) => {
                return { text: item, style: "tableBody" };
            });
            return formatObj;
        });

        const tableBody = [
            [
                { text: "NIP", style: "tableHeader" },
                { text: "Nama", style: "tableHeader" },
                { text: "Tanggal", style: "tableHeader" },
                { text: "Waktu Masuk", style: "tableHeader" },
                { text: "Waktu Keluar", style: "tableHeader" },
                { text: "Jam Absen", style: "tableHeader" },
                { text: "Total Jam", style: "tableHeader" },
            ],
            ...mapData,
        ];

        const title = this.getPrintTitle();
        const docDefinition = {
            pageSize: "A4",
            pageMargins: [40, 120, 40, 60],
            header: commonHeaderTemplate(title),
            content: [
                {
                    style: "tableStyle",
                    headerRows: 1,
                    table: {
                        widths: [120, ...Array(6).fill(50)],
                        body: tableBody,
                    },
                    layout: "lightHorizontalLines",
                },
            ],
            styles: {
                tableHeader: {
                    fontSize: 10,
                    bold: true,
                    margin: [4, 4, 4, 4],
                },
                tableBody: {
                    fontSize: 10,
                    margin: [4, 4, 4, 4],
                },
            },
        };
        // open the PDF in a new window
        pdfMake.createPdf(docDefinition).open();
    }

    printPerId(id) {
        const data = this.data.find((item) => item.id === parseInt(id, 10));
        const absensi = data.absensi[0] || {};

        if (!data) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Data tidak ditemukan!",
            });
            return;
        }

        const transform = (_data) => ({
            margin: [0, 0, 0, 10],
            table: {
                widths: ["auto", "*"],
                body: _data.map((item) => [
                    { text: item[0], style: "tableHeader" },
                    { text: item[1], style: "tableBody" },
                ]),
            },
        });

        const PROFILE = transform([
            ["Nama", data.nama],
            ["NIP", data.nip],
            ["Jabatan", `${data.jabatan} - ${get(data, 'bidangs.nama', 'Tidak ada')}`],
            ["Jenis Kelamin", data.jk],
            ["Alamat", data.alamat],
            ["No. HP", data.no_telp],
        ]);

        const ABSENSI = transform([
            ["Tanggal", absensi.formatted_tanggal],
            ["Jam Absen", absensi.formatted_shift],
            ["Waktu Masuk", absensi.formatted_waktu_masuk],
            ["Waktu Keluar", absensi.formatted_waktu_keluar],
            ["Total Jam", absensi.total_jam],
            ["Lokasi Masuk", absensi.lokasi_masuk],
            ["Lokasi Keluar", absensi.lokasi_keluar],
            ["Jarak Masuk", absensi.jarak_masuk],
            ["Jarak Keluar", absensi.jarak_keluar],
        ]);

        const dd = {
            pageSize: "A4",
            pageMargins: [40, 120, 40, 60],
            header: commonHeaderTemplate("Laporan Absensi Harian"),
            content: [
                {
                    text: "Profil",
                    style: "header",
                },
                PROFILE,
                {
                    text: "Absensi",
                    style: "header",
                },
                ABSENSI,
            ],
            styles: {
                header: {
                    fontSize: 14,
                    bold: true,
                    margin: [0, 0, 0, 10],
                },
                tableHeader: {
                    fontSize: 12,
                    bold: true,
                    margin: [4, 4, 4, 4],
                },
                tableBody: {
                    fontSize: 12,
                    margin: [4, 4, 4, 4],
                },
            },
        };

        // open the PDF in a new window
        pdfMake.createPdf(dd).open();
    }
}
