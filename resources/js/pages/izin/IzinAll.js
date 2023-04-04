import axios from "axios";
// import $ from "jquery";
import { get } from "lodash";
import moment from "moment";
import * as yup from "yup";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.addVirtualFileSystem(pdfFonts);

import { commonHeaderTemplate } from "../../misc/print-template";
import { swalConfirmator } from "../../misc/swal-template";
import { renderYupErrors } from "../../utils/dom";
import { pdfOrImage } from "../../utils/yup-tester";
import Swal from "sweetalert2";

const successSwal = (text) =>
    Swal.fire({
        title: "Berhasil!",
        text,
        icon: "success",
    });

const warningSwal = (confirmText) =>
    Swal.fire({
        title: "Lanjutkan?",
        text: confirmText,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, lanjutkan!",
        cancelButtonText: "Tidak",
    });

const dataTableOptions = {
    language: {
        url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json",
    },
};

export default class IzinAll {
    constructor() {
        this.state = {};
        this.data = [];
        // this.init();
        document.addEventListener("livewire:load", () => {
            this.initLivewire();
        });
    }

    initLivewire() {
        const LW = window.livewire;

        // $("table").DataTable(dataTableOptions);
        LW.on("initDataTable", () => {
            $("table").DataTable(dataTableOptions);
        });

        LW.on("successAddIzin", () => {
            successSwal("Pengajuan izin berhasil ditambahkan").then(() => {
                window.location.reload();
            });
        });

        LW.on("successUpdateIzin", () => {
            successSwal("Pengajuan izin berhasil diubah").then(() => {
                window.location.reload();
            });
        });

        LW.on("deleteIzin", (id) => {
            console.log("delete izin");
            warningSwal("Pengajuan izin akan dihapus").then((result) => {
                if (result.value) {
                    LW.emit("procceedDeleteIzin", id);
                }
            });
        });

        LW.on("accIzin", (id) => {
            warningSwal("Pengajuan izin akan disetujui").then((result) => {
                if (result.value) {
                    LW.emit("procceedAccIzin", id);
                }
            });
        });

        LW.on("rejectIzin", (id) => {
            warningSwal("Pengajuan izin akan ditolak").then((result) => {
                if (result.value) {
                    LW.emit("procceedRejectIzin", id);
                }
            });
        });

        LW.on("success", (message) => {
            successSwal(message).then(() => {
                window.location.reload();
            });
        });
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
        this.fetchData();

        this.isEdit = false;

        this.initForm();
        this.initEditForm();
        this.initTableActions();

        const btnCancelIncoming = $("#btn-cancel-incoming");
        btnCancelIncoming.on("click", (e) => {
            const dataId = $(e.target).data().id;
            this.delete(dataId);
        });
    }

    initTableActions() {
        const btnTracks = $(".btn-track");
        btnTracks.on("click", (e) => {
            const data = $(e.target).data().tracking || [];
            this.renderTracking(data);
        });

        const btnDeletes = $(".btn-delete");
        btnDeletes.on("click", (e) => {
            const id = $(e.target).data().id;
            this.delete(id);
        });

        const btnAccIzins = $(".acc-izin");
        btnAccIzins.on("click", (e) => {
            const id = $(e.target).data().id;
            this.accIzin(id);
        });

        const btnRejectIzins = $(".reject-izin");
        btnRejectIzins.on("click", (e) => {
            const id = $(e.target).data().id;
            this.rejectIzin(id);
        });

        const btnPrints = $(".print-izin");
        btnPrints.on("click", (e) => {
            const id = $(e.target).data().id;
            this.print(id);
        });
    }

    initEditForm() {
        const updateURL = `${this.state.baseUrl}/panel/izin/`;

        const btnEdits = $(".btn-edit");
        const formAdd = $("#form-add");
        const modalTitle = $(".modal-title");
        const submitBtn = $("[type=submit]");
        const inputs = formAdd.find("input, select");

        btnEdits.on("click", (e) => {
            console.log("edit");
            const data = $(e.target).data().item || {};
            inputs.each((i, el) => {
                const input = $(el);
                const name = input.attr("name");
                const type = input.attr("type");
                const val = get(data, name, "");

                if (name === "_token") return;
                if (type === "file") {
                    const template = `<div class="text-muted file-edit-value">${val}</div>`;
                    if (input.next().hasClass("file-edit-value")) {
                        input.next().remove();
                    }
                    input.after(template);
                } else {
                    input.val(val);
                }
            });

            const id = data.id;
            formAdd.attr("action", `${updateURL}${id}`);
            if (formAdd.find("input[name=_method]").length) {
                formAdd.find("input[name=_method]").remove();
            } else {
                formAdd.append(
                    `<input type="hidden" name="_method" value="PUT">`
                );
            }

            modalTitle.text("Edit Izin");
            submitBtn.text("Update");
            this.isEdit = true;
        });
        // on modal close
        $("#modal-form").on("hidden.bs.modal", function (e) {
            modalTitle.text("Tambah Pengajuan");
            submitBtn.text("Ajukan");

            $(".invalid-feedback").remove();
            $(".is-invalid").removeClass("is-invalid");
            $(".text-muted").remove();
            $(".file-edit-value").remove();

            formAdd[0].reset();
            formAdd.attr("action", this.state.baseURL);
            this.isEdit = false;
        });
    }

    initForm() {
        // add event on change or keyup to each form-control or form-control-file classes
        const formControl = $(".form-control, .form-control-file");
        formControl.on("change keyup", (e) => {
            const input = $(e.target);
            // if contains class is-invalid then remove it
            if (input.hasClass("is-invalid")) {
                input.removeClass("is-invalid");
            }
            // if after input has class invalid-feedback then remove it
            if (input.next().hasClass("invalid-feedback")) {
                input.next().remove();
            }
        });

        const selectJenis = $("#jenis");
        selectJenis.on("change", (e) => {
            const val = $(e.target).val();
            // if jenis === 'Lainnya' then append input
            const inputLainnya = $("#jenis-lainnya");
            if (val === "Lainnya") {
                const template = `
                    <div class="form-group">
                        <label for="jenis-lainnya">Jenis Lainnya</label>
                        <input type="text" name="jenis_lainnya" id="jenis-lainnya" required class="form-control" placeholder="Jenis Lainnya">
                    </div>
                `;
                inputLainnya.append(template);
            } else {
                inputLainnya.empty();
            }
        });

        const formAdd = $("#form-add");
        formAdd.on("submit", (e) => {
            e.preventDefault();
            this.add(e);
        });
    }

    fetchData() {
        // const jsonURL = `${this.state.currentUrl}?mode=json`;
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        params.set("mode", "json");
        const jsonURL = `${this.state.currentUrl}?${params.toString()}`;
        $.getJSON(jsonURL, (data) => {
            this.state.data = data;
        });
    }

    add(e) {
        // serialize form data
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        console.log(data);

        // yup validation
        const schema = yup.object().shape({
            jenis: yup.string().required("Jenis izin harus diisi"),
            jenis_lainnya: yup.string().when("jenis", {
                is: "Lainnya",
                then: yup.string().required("Jenis lainnya harus diisi"),
            }),
            tgl_mulai: yup.date().required("Tanggal mulai harus diisi"),
            tgl_selesai: yup.date().required("Tanggal selesai harus diisi"),
            keterangan: yup
                .string()
                .min(10, "Keterangan minimal 10 karakter")
                .required("Keterangan harus diisi"),
            bukti: pdfOrImage(this.isEdit).test(
                "required",
                "Bukti harus diisi",
                (value) => {
                    if (this.isEdit) {
                        return true;
                    }
                    return value.size > 0;
                }
            ),
        });
        schema
            .validate(data, { abortEarly: false })
            .then(() => {
                e.target.submit();
            })
            .catch((err) => {
                renderYupErrors(err);
            });
    }

    delete(id) {
        import("sweetalert2").then((Swal) => {
            Swal.default
                .fire({
                    title: "Apakah anda yakin?",
                    text: "Data tidak dapat dikembalikan setelah dihapus",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus!",
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement("form");
                        form.method = "POST";
                        form.action = `/panel/izin/${id}`;
                        const payload = {
                            _method: "DELETE",
                            _token: this.state.token,
                        };
                        Object.keys(payload).forEach((key) => {
                            const input = document.createElement("input");
                            input.type = "hidden";
                            input.name = key;
                            input.value = payload[key];
                            form.appendChild(input);
                        });
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
        });
    }

    accIzin(id) {
        swalConfirmator(
            "Terima pengajuan ini?",
            'Pengajuan akan diterima dan status akan berubah menjadi "Diterima"',
            "Ya, terima!",
            (Swal) => {
                const url = this.state.currentUrl;
                let status = `accepted_${this.state.role}`;
                if (this.state.role === "atasan") {
                    status = status.replace("atasan", "pimpinan");
                }
                const payload = {
                    _method: "put",
                    _token: this.state.token,
                    status,
                };
                axios
                    .post(`${url}/update_status/${id}`, payload)
                    .then((res) => {
                        if (get(res, "data.status") === "success") {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Pengajuan berhasil diterima",
                                icon: "success",
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: "Pengajuan gagal diterima",
                                icon: "error",
                            });
                        }
                    });
            }
        );
    }

    rejectIzin(id) {
        swalConfirmator(
            "Tolak pengajuan ini?",
            "Pengajuan akan ditolak dan status akan berubah menjadi 'Ditolak'",
            "Ya, tolak!",
            (Swal) => {
                const url = this.state.currentUrl;
                const payload = {
                    _method: "put",
                    _token: this.state.token,
                    status: "rejected",
                };
                axios
                    .post(`${url}/update_status/${id}`, payload)
                    .then((res) => {
                        if (get(res, "data.status") === "success") {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Pengajuan berhasil ditolak",
                                icon: "success",
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: "Pengajuan gagal ditolak",
                                icon: "error",
                            });
                        }
                    });
            }
        );
    }

    renderTracking(data = []) {
        const html = data
            .map((item) => {
                const formattedDate = moment(item.date).format("DD MMMM YYYY");
                const formattedDateTime = moment(item.date).format(
                    "HH:mm [WIB]"
                );
                const template = `<div class="tracking-item">
                <div class="tracking-icon status-intransit">
                    <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                    </svg>
                </div>
                <div class="tracking-date">${formattedDate}<span>${formattedDateTime}</span></div>
                <div class="tracking-content">${item.status
                    .toString()
                    .toUpperCase()}</div>
            </div>`;
                return template;
            })
            .join("");
        $("#tracking-list").html(html);
    }

    print(id) {
        const data = this.state.data.find((item) => item.id === id);

        const CONTENT_BODY = [
            ["Nama", data.user.nama],
            ["NIP", data.user.nip],
            [
                "Jabatan",
                `${data.user.jabatan} ${get(data, "user.bidangs.nama")}`,
            ],
            ["Golongan", data.user.golongan],
            ["Jenis Izin", data.jenis],
            ["Tanggal Mulai", data.formatted_tgl_mulai],
            ["Tanggal Selesai", data.formatted_tgl_selesai],
            ["Durasi", data.formatted_durasi],
            ["Keterangan", data.keterangan],
            ["Status", data.status.text],
        ].map((item) => {
            return [
                {
                    text: item[0],
                    style: "tableHeader",
                },
                {
                    text: item[1],
                    style: "tableContent",
                },
            ];
        });

        const docDefinition = {
            pageSize: "A4",
            pageOrientation: "portrait",
            pageMargins: [40, 120, 40, 60],
            header: commonHeaderTemplate("Detail Pengajuan Izin"),
            content: [
                {
                    style: "tableExample",
                    table: {
                        widths: ["auto", "*"],
                        heights: [20, 20, 20, 20, 20, 20, 20, 20, 20, 20],
                        body: CONTENT_BODY,
                    },
                },
            ],
            styles: {
                tableHeader: {
                    bold: true,
                    fontSize: 12,
                    color: "black",
                },
                tableContent: {
                    fontSize: 12,
                    color: "black",
                },
            },
        };

        pdfMake.createPdf(docDefinition).open();
    }
}
