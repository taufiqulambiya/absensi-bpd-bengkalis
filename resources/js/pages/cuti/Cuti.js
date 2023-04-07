import moment from "moment";
import Swal from "sweetalert2";
import { addCutiScheme } from "../../scheme/add-cuti";

const dataTableOptions = {
    language: {
        url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json",
    },
};

const swalConfirm = (text) =>
    Swal.fire({
        title: "Lanjutkan?",
        text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Lanjutkan!",
    });

const swalSuccess = (text) =>
    Swal.fire({
        title: "Berhasil",
        text,
        icon: "success",
        confirmButtonText: "OK",
    });

class Cuti {
    constructor() {
        this.state = {};
        this.data = {};

        // this.jatahCuti = 0;
        this.initLivewire();
        this.init();
    }

    initLivewire() {
        const LW = window.Livewire;

        // $("table").DataTable(dataTableOptions);

        LW.on("initDataTable", () => {
            $(".datatable").DataTable(dataTableOptions);
        });

        LW.on("acc", (id) => {
            swalConfirm("Data cuti akan di ACC").then((result) => {
                if (result.isConfirmed) {
                    LW.emit("accCuti", id);
                }
            });
        });

        LW.on("rej", (id) => {
            swalConfirm("Data cuti akan ditolak").then((result) => {
                if (result.isConfirmed) {
                    LW.emit("rejectCuti", id);
                }
            });
        });

        LW.on("successAccCuti", () => {
            swalSuccess("Data cuti berhasil di ACC");
        });

        LW.on("successRejectCuti", () => {
            swalSuccess("Data cuti berhasil ditolak");
        });

        LW.on("delete", (id) => {
            swalConfirm("Data cuti akan dihapus").then((result) => {
                if (result.isConfirmed) {
                    LW.emit("deleteCuti", id);
                }
            });
        });

        LW.on("successDeleteCuti", () => {
            Swal.fire({
                title: "Berhasil",
                text: "Data cuti berhasil dihapus",
                icon: "success",
                confirmButtonText: "OK",
            }).then(() => {
                window.location.reload();
            });
        });

        LW.on("rerenderDatePicker", (newJatahCuti) => {
            this.renderMultiDatePicker(newJatahCuti);
        });
    }

    init() {
        $(".data-container").each((_, el) => {
            const data = $(el).data();
            Object.entries(data).forEach(([key, value]) => {
                this.state[key] = value;
            });
        });
        // console.log(this.state);

        if (this.state.currentUrl) {
            this.getData();
        }

        window.Livewire.on("cutiSubmitted", (isEdit) => {
            Swal.fire({
                title: "Berhasil",
                text: isEdit
                    ? "Data cuti berhasil diubah"
                    : "Data cuti berhasil ditambahkan",
                icon: "success",
                confirmButtonText: "OK",
            }).then(() => {
                window.location.reload();
            });
        });

        // $("#jenis-jatah").on("change", function () {
        //     // get the data attribute of option selected
        //     const data = $(this).find(":selected").data();
        //     const { value } = data;
        //     $("#jtct-value").text(value);
        // });
        const inputTanggal = document.querySelector("#tanggal");
        window.Livewire.on("changeTanggal", (val) => {
            inputTanggal.value = val;
            inputTanggal.dispatchEvent(new Event("input"));
        });

        $("#modal-form").on("show.bs.modal", () => {
            // const select = $("#jcf-selector");
            this.renderMultiDatePicker();
        });

        $("#modal-form").on("hidden.bs.modal", function (e) {
            $("#ctmdp").multiDatesPicker("resetDates", "picked");
            $("input").val("");
        });
    }

    initModalForm() {
        const jenisJatahSelector = document.querySelector("#jcf-selector");
        jenisJatahSelector.addEventListener("change", (event) => {
            const value = event.target.selectedOptions[0].dataset.value;
            $("#jcf-value").text(value);
            this.jatahCuti = value;

            const dates = $("#ctmdp").multiDatesPicker("getDates");
            const total = dates.length;
            if (total > this.jatahCuti) {
                Swal.fire(
                    "Gagal",
                    "Jumlah hari yang dipilih melebihi jatah cuti",
                    "error"
                );
                const newDates = dates.slice(0, this.jatahCuti);
                $("#ctmdp").multiDatesPicker("resetDates", "picked");
                $("#ctmdp").multiDatesPicker("addDates", newDates);
            }
        });

        $("#modal-form").on("show.bs.modal", () => {
            const select = $("#jcf-selector");
            const data = select.find(":selected").data();
            this.jatahCuti = data.value;

            const today = moment();
            const end = moment().endOf("year");
            const options = {
                beforeShowDay: $.datepicker.noWeekends,
                minDate: 1,
                maxDate: end.diff(today, "days"),
                autoSize: true,
                onSelect: (dateText, inst) => {
                    const dates = $("#ctmdp").multiDatesPicker("getDates");
                    const total = dates.length;
                    if (total > this.jatahCuti) {
                        Swal.fire(
                            "Gagal",
                            "Jumlah hari yang dipilih melebihi jatah cuti",
                            "error"
                        );
                        // remove date
                        $("#ctmdp").multiDatesPicker(
                            "removeDates",
                            new Date(dateText)
                        );
                    }
                },
            };
            if ((this.data.disable_dates || []).length > 0) {
                options.addDisabledDates = this.data.disable_dates;
            }

            $("#ctmdp").multiDatesPicker(options);
        });

        $("#modal-form").on("hidden.bs.modal", function (e) {
            $("#ctmdp").multiDatesPicker("resetDates", "picked");
            $("input").val("");
        });

        const btnSubmit = $("button[type='submit']");
        const btnCloseModal = $('button[data-dismiss="modal"]');
        btnSubmit.on("click", (event) => {
            const jenisCuti = $("#jcf-selector").val();
            const dates = $("#ctmdp").multiDatesPicker("getDates");
            const keterangan = $("#keterangan").val();
            const bukti = $("#bukti").val();

            $(".invalid-feedback").remove();
            $(".form-group").removeClass("has-error");
            $("input").removeClass("is-invalid");

            const scheme = addCutiScheme;

            scheme
                .validate({
                    jenis_cuti: jenisCuti,
                    keterangan,
                    dates,
                    bukti,
                })
                .then((data) => {
                    btnSubmit.attr("disabled", true);
                    btnSubmit.text("Loading...");
                    btnCloseModal.attr("disabled", true);
                    $(".modal-backdrop").off("click");

                    const formData = this.populateFormData(data);
                    const buktiEl = document.querySelector("#bukti");
                    if (buktiEl.files.length > 0) {
                        formData.append("bukti", buktiEl.files[0]);
                    }

                    const { currentUrl } = this.state;
                    $.ajax({
                        url: currentUrl,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: (response) => {
                            console.log(response);
                            if (response.status === "error") {
                                Swal.fire("Gagal", response.message, "error");
                                return;
                            }
                            Swal.fire(
                                "Berhasil",
                                "Pengajuan cuti berhasil dibuat.",
                                "success"
                            );
                            $("#modal-form").modal("hide");
                            location.reload();
                        },
                        error: (err) => {
                            console.log(err);
                            Swal.fire(
                                "Gagal",
                                "Pengajuan cuti gagal dibuat.",
                                "error"
                            );
                        },
                        complete: () => {
                            btnSubmit.attr("disabled", false);
                            btnSubmit.text("Submit");
                            btnCloseModal.attr("disabled", false);
                        },
                    });
                })
                .catch((err) => {
                    const path = err.path;
                    let formGroup = $(`#${path}`).closest(".form-group");
                    if (path === "dates") {
                        formGroup = $("#ctmdp").closest(".form-group");
                    }
                    const input = $(`#${path}`);
                    const invalidFeedback = `<div class="invalid-feedback">${err.message}</div>`;
                    input.addClass("is-invalid");
                    formGroup.addClass("has-error");
                    formGroup.append(invalidFeedback);
                });
        });
    }

    getData() {
        const { currentUrl } = this.state;
        $.getJSON(`${currentUrl}?mode=json`, (data) => {
            this.data = data;
            // this.initModalForm();
            // console.log(data);
        });
    }

    populateFormData(data) {
        const { idUser: id_user, token: _token } = this.state;
        const dates = data.dates.map((date) => {
            return moment(date).format("YYYY-MM-DD");
        });
        const total = dates.length;
        const tanggal = dates.join(",");
        const tracking = [
            {
                status: "Pengajuan dibuat.",
                date: moment().format("YYYY-MM-DD HH:mm:ss"),
            },
        ];

        const payload = {
            _token,
            id_user,
            jenis: data.jenis_cuti,
            keterangan: data.keterangan,
            tanggal,
            tracking: JSON.stringify(tracking),
            total,
            status: "pending",
        };
        const formData = new FormData();
        Object.entries(payload).forEach(([key, value]) => {
            formData.append(key, value);
        });
        return formData;
    }

    clearIfMoreThanJatahCuti(jatahCuti) {
        const dates = $("#ctmdp").multiDatesPicker("getDates");
        const total = dates.length;
        if (total > jatahCuti) {
            Swal.fire(
                "Gagal",
                "Jumlah hari yang dipilih melebihi jatah cuti",
                "error"
            );
            // remove date
            $("#ctmdp").multiDatesPicker("removeDates", dates);

            window.Livewire.emit("changeTanggal", "");
            return;
        }
    }

    renderMultiDatePicker(newJatahCuti) {
        const jatahCuti = $("#jatah-cuti-value").text();

        const today = moment();
        const end = moment().endOf("year");
        const options = {
            beforeShowDay: $.datepicker.noWeekends,
            minDate: 1,
            maxDate: end.diff(today, "days"),
            autoSize: true,
            onSelect: (dateText, inst) => {
                const dates = $("#ctmdp").multiDatesPicker("getDates");
                const total = dates.length;

                console.log(total, jatahCuti);

                this.clearIfMoreThanJatahCuti(jatahCuti);
                
                window.Livewire.emit("changeTanggal", dates);
            },
        };
        if ((this.data.disable_dates || []).length > 0) {
            console.log(this.data.disable_dates);
            options.addDisabledDates = this.data.disable_dates;
        }

        if (newJatahCuti) {
            this.clearIfMoreThanJatahCuti(newJatahCuti);
        }

        $("#ctmdp").multiDatesPicker(options);
        window.Livewire.on("fillDatePicker", (dateString) => {
            const dates = dateString.split(",").map((date) => new Date(date));
            $("#ctmdp").multiDatesPicker("resetDates", "picked");
            $("#ctmdp").multiDatesPicker("addDates", dates);
        });
    }
}

export default Cuti;
