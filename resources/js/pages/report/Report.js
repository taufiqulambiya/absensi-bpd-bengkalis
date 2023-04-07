import { commonHeaderTemplate } from "../../misc/print-template";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.addVirtualFileSystem(pdfFonts);

export default class Report {
    constructor() {
        // this.state = {};
        // this.dataType = "pegawai";
        // this.inputIds = ["pegawai-select"];
        // this.init();
        // this.initLivewire();
    }

    initLivewire() {
        const LW = window.livewire;
        const initJS = () => {
            // $("#pegawai-select").selectize({
            //     plugins: ["remove_button"],
            //     delimiter: ",",
            //     persist: false,
            //     create: function (input) {
            //         return {
            //             value: input,
            //             text: input,
            //         };
            //     },
            //     onChange: (values) => {
            //         LW.emit("setPegawaiIds", values);
            //     },
            // });
        };

        // LW.on("initJS", () => {
        //     initJS();
        // });

        // LW.on("print", (url) => {
        //     window.open(url, "_blank");
        // });

        // initJS();
    }

    init() {
        const dataContainer = $(".data-container");
        dataContainer.each((index, item) => {
            const data = $(item).data();
            Object.entries(data).forEach(([key, value]) => {
                this.state[key] = value;
            });
        });

        console.log(this.state);

        this.renderByJenisData();
        const jenisDataSelect = $("#jenis-data");
        jenisDataSelect.on("change", (e) => {
            const value = e.target.value;
            this.dataType = value;
            this.renderByJenisData();
        });

        const btnPrint = $("#btn-print");
        btnPrint.on("click", () => {
            this.print();
        });
    }

    initInputs() {
        $(".multiselect").each(function () {
            $(this).select2({
                width: "100%",
                placeholder: `Pilih ${$(this).data("label")}...`,
            });
        });
        $(".drPicker").daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: "Clear",
                format: "DD/MM/YYYY",
            },
        });
        $(".drPicker").on("apply.daterangepicker", function (ev, picker) {
            $(this).val(
                picker.startDate.format("DD/MM/YYYY") +
                    " - " +
                    picker.endDate.format("DD/MM/YYYY")
            );
        });
    }

    renderByJenisData() {
        const renderEl = $("#render-by-jenis-data");
        console.log(this.dataType);
        switch (this.dataType) {
            case "pegawai":
                this.inputIds = ["pegawai-select"];
                renderEl.html(this.renderPegawai());
                break;
            case "absensi":
                this.inputIds = ["pegawai-select", "range"];
                renderEl.html(this.renderPegawaiAndRange());
                break;
            case "izin":
                this.inputIds = ["pegawai-select", "range", "jenis-izin"];
                renderEl.html(this.getTemplateIzin());
                break;
            case "cuti":
                this.inputIds = ["pegawai-select", "range", "jenis-cuti"];
                renderEl.html(this.getTemplateCuti());
                break;
            case "dinas-luar":
                this.inputIds = ["pegawai-select", "range"];
                renderEl.html(this.renderPegawaiAndRange());
            default:
                break;
        }
        this.initInputs();
    }

    renderPegawai() {
        return `<div class="form-group mb-3">
            <label for="pegawai">Pilih Pegawai</label>
            <select class="form-control multiselect" name="pegawai[]" id="pegawai-select" multiple data-label="Pegawai" data-item="pegawai">
            ${this.state.pegawai
                .map((item) => {
                    return `<option value="${item.id}">${item.nama}</option>`;
                })
                .join("")}
            </select>
        </div>`;
    }

    renderPegawaiAndRange() {
        const templatePegawai = this.renderPegawai();
        return `<div>
            ${templatePegawai}
            <div class="form-group">
                <label for="range-absensi">Range Tanggal</label>
                <input type="text"
                    class="form-control drPicker" name="range-absensi" id="range" data-label="Range" aria-describedby="helpId" placeholder="Range Tanggal...">
            </div>
        </div>`;
    }

    getTemplateIzin() {
        const templateAbsensi = this.renderPegawaiAndRange();
        return `<div>
            ${templateAbsensi}
            <div class="form-group">
                <label for="jenis-izin">Jenis</label>
                <select name="jenis-izin[]" id="jenis-izin" class="form-control multiselect" multiple data-label="Jenis Izin">
                    <option value="Sakit">Sakit</option>
                    <option value="Urusan Keluarga">Urusan Keluarga</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
        </div>`;
    }

    getTemplateCuti() {
        const templateAbsensi = this.renderPegawaiAndRange();
        return `<div>
            ${templateAbsensi}
            <div class="form-group">
                <label for="jenis-cuti">Jenis</label>
                <select name="jenis-cuti" id="jenis-cuti" class="form-control multiselect" data-label="Jenis Cuti" multiple>
                    <option value="tahunan">Cuti Tahunan</option>
                    <option value="besar">Cuti Besar</option>
                    <option value="melahirkan">Cuti Melahirkan</option>
                    <option value="penting">Cuti Alasan Penting</option>
                    <option value="ctln">Cuti Diluar Tanggungan Negara</option>
                </select>
            </div>
        </div>`;
    }

    getImageBase64(imageUrl) {
        if (imageUrl === null || imageUrl === "") {
            return "";
        }
        const url = this.state.currentUrl + "/get_image_base64/" + imageUrl;
        return $.ajax({
            url,
            type: "GET",
            dataType: "json",
            success: function (response) {
                return response;
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    print() {
        const fields = this.inputIds;

        let isValid = true;
        fields.forEach((field) => {
            const input = $(`#${field}`);
            if (input.val() === null || input.val() === "") {
                input.addClass("is-invalid");
                const invalidFeedback = `<div class="invalid-feedback">Field ${input.data(
                    "label"
                )} tidak boleh kosong</div>`;
                if (input.parent().find(".invalid-feedback").length === 0) {
                    input.parent().append(invalidFeedback);
                } else {
                    input.parent().find(".invalid-feedback").remove();
                    input.parent().append(invalidFeedback);
                }
                isValid = false;
            } else {
                input.removeClass("is-invalid");
            }
        });

        if (isValid) {
            const payload = {
                _method: "POST",
                _token: this.state.token,
                filter: this.dataType,
            };
            fields.forEach((field) => {
                const input = $(`#${field}`);
                payload[field] = input.val();
            });
            const url = this.state.currentUrl;
            $.post(url, payload, (response) => {
                const data = response || [];
                this.printByJenisData(data);
            });
        }
    }

    printByJenisData(data) {
        const type = this.dataType;
        switch (type) {
            case "pegawai":
                this.printPegawai(data);
                break;
            case "absensi":
                this.printAbsensi(data);
                break;
            default:
                break;
        }
    }

    async printPegawai(data) {
        const CONTENT_BODY = await Promise.all(
            data
                .map((item, idx) => [
                    [`#${idx + 1}`, ""],
                    ["Nama", item.nama],
                    ["NIP", item.nip],
                    ["Jabatan", item.jabatan],
                    ["Bidang", item.bidangs.nama],
                    ["Golongan", item.golongan],
                    ["Jatah Cuti", item.jatah_cuti],
                    ["Tanggal Lahir", item.tgl_lahir],
                    ["Jenis Kelamin", item.jk],
                    ["Alamat", item.alamat],
                    ["No. Telp", item.no_telp],
                    // ["Gambar", {
                    //     image: await this.getImageBase64(item.gambar),
                    //     width: 100,
                    //     height: 100,
                    // }],
                    [
                        {
                            text: " ",
                            colSpan: 2,
                            margin: [0, 20, 0, 0],
                        },
                    ],
                ])
                .flat()
        );
        console.log(CONTENT_BODY);
        const dd = {
            pageSize: "A4",
            pageMargins: [40, 100, 40, 60],
            header: commonHeaderTemplate("Laporan Data Pegawai"),
            content: [
                {
                    layout: "lightHorizontalLines",
                    table: {
                        headerRows: 1,
                        widths: [100, "*"],
                        heights: Array(CONTENT_BODY.length).fill(20),
                        margin: [0, 20, 0, 20],
                        body: CONTENT_BODY,
                    },
                },
            ],
            styles: {
                tableHeader: {
                    bold: true,
                    fontSize: 13,
                    color: "black",
                },
            },
        };
        pdfMake.createPdf(dd).open();
    }

    printAbsensi(data) {
        const CONTENT_BODY = data.map((item, idx) => []);
    }
}
