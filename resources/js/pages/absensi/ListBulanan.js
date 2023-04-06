import $ from "jquery";
import { get } from "lodash";
import moment from "moment";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
import { commonHeaderTemplate } from "../../misc/print-template";
pdfMake.addVirtualFileSystem(pdfFonts);

export default class ListBulanan {
    constructor() {
        console.log("ListBulanan");
        this.state = {};
        this.data = [];
        // this.init();
    }

    init() {
        const dataContainer = $(".data-container");
        dataContainer.each((_, item) => {
            const data = $(item).data();
            Object.entries(data).forEach(([key, value]) => {
                this.state[key] = value;
            });
        });

        console.log(this.state);
        this.initFilterBulan();
        this.initClickItem();
        this.initPrintAllData();
    }

    initFilterBulan() {
        const filterBulanEl = $("#filter-bulan");
        filterBulanEl.on("change", (e) => {
            const value = e.target.value;
            const url = new URL(window.location.href);
            url.searchParams.set("bulan", value);
            window.open(url, "_self");
        });
    }

    initClickItem() {
        const clickables = $(".clickable");
        console.log(clickables);
        clickables.on("click", (e) => {
            console.log(e.target);
            const id = e.target.dataset.id;
            const path = `panel/absensi/${id}`;
            const url = new URL(path, window.location.origin);
            window.open(url, "_self");
        });
    }

    initPrintAllData() {
        $("#print-all-bulanan").on("click", () => {
            this.printAllData();
        });
    }

    printAllData() {
        const data = this.state.data || [];
        const days = this.state.days || [];

        const start = moment(days[0]);
        const end = moment(days[days.length - 1]);
        const printTitle = `Laporan Absensi Periode, ${moment(start).format(
            "DD/MM/YYYY"
        )} - ${moment(end).format("DD/MM/YYYY")}`;
        console.log(printTitle);

        const content = {
            stack: data.map((user) => {
                let tableData = [
                    [{
                        text: "Tidak ada data",
                        colSpan: 5,
                        alignment: "center",
                        margin: [0, 20, 0, 20],
                    }]
                ];
                if (user.absensi.length > 0) {
                    tableData = user.absensi.map((absensi) => [
                        moment(new Date(absensi.tanggal)).format("DD/MM/YYYY"),
                        moment(absensi.waktu_masuk, "HH:mm:ss").format("HH:mm") + " WIB",
                        absensi.waktu_keluar ? moment(absensi.waktu_keluar, "HH:mm:ss").format("HH:mm") + " WIB" : "",
                        get(absensi, "jam_absen", "-"),
                        get(absensi, "total_jam", "-"),
                    ]);
                }
                return {
                    stack: [
                        {
                            text: user.nama,
                            margin: [0, 0, 0, 10],
                        },
                        {
                            margin: [0, 0, 0, 20],
                            table: {
                                widths: "*",
                                body: [
                                    [
                                        "Tanggal",
                                        "Waktu Masuk",
                                        "Waktu Keluar",
                                        "Shift",
                                        "Total Jam Kerja",
                                    ],
                                    ...tableData,
                                ],
                            },
                        },
                    ],
                };
            }),
        };
        const dd = {
            pageSize: "A4",
            pageMargins: [40, 120, 40, 60],
            header: commonHeaderTemplate(printTitle),
            content,
            styles: {
                header: {
                    fontSize: 18,
                    bold: true,
                    margin: [0, 0, 0, 10],
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
