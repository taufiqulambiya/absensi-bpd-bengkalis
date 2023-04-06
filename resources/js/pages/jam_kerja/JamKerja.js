// import 'select2';
// import $ from 'jquery';
import Swal from "sweetalert2";
import * as yup from "yup";
import { swalConfirmator } from "../../misc/swal-template";

export default class JamKerja {
    constructor() {
        this.state = {};
        // this.init();
    }

    init() {
        $(".data-container").each((i, el) => {
            const data = $(el).data();
            Object.entries(data).forEach(([key, value]) => {
                this.state[key] = value;
            });
        });

        this.initForm();

        const btnDeletes = $(".btn-delete");
        btnDeletes.on("click", (e) => {
            const id = $(e.currentTarget).data("id");
            this.delete(id);
        });

        const btnEdits = $(".btn-edit");
        btnEdits.on("click", (e) => {
            const id = $(e.currentTarget).data("id");
            this.edit(id);
        });

        const switchStatus = $(".switch-status");
        switchStatus.on("change", (e) => {
            const id = $(e.currentTarget).data("id");
            const value = $(e.currentTarget).prop("checked");
            const strValue = value ? "aktif" : "nonaktif";
            this.switchStatus(id, strValue);
        });
    }

    initForm() {
        const form = $("#modal-form form");
        $("[name='days[]']").select2();

        form.on("submit", (e) => {
            e.preventDefault();

            const exclude = ["_token", "_method"];
            const data = form.serializeArray().reduce((obj, item) => {
                if (!exclude.includes(item.name)) {
                    obj[item.name] = item.value;
                }
                return obj;
            }, {});

            // remove all error
            $(".form-group").removeClass("has-error");
            $("input, select").removeClass("is-invalid");
            $(".form-group .invalid-feedback").remove();
            const scheme = yup.object().shape({
                "days[]": yup.string().required("Pilih hari kerja"),
                keterangan: yup.string().required("Keterangan harus diisi"),
                selesai: yup
                    .string()
                    .required("Jam selesai harus diisi")
                    .test(
                        "test",
                        "Jam selesai harus lebih besar dari jam mulai",
                        function (value) {
                            return value > this.parent.mulai;
                        }
                    ),
                mulai: yup.string().required("Jam mulai harus diisi"),
            });
            scheme
                .validate(data)
                .then(() => {
                    // continue submit using default
                    // form.submit() is deprecated
                    form[0].submit();
                })
                .catch((err) => {
                    const { path, message } = err;
                    const input = form.find(`[name="${path}"]`);
                    const formGroup = input.closest(".form-group");
                    formGroup.addClass("has-error");
                    const invalidFeedback = `<div class="invalid-feedback">${message}</div>`;
                    formGroup.append(invalidFeedback);
                });
        });
    }

    delete(id) {
        console.log("delete", id);
        console.log(this.state);

        swalConfirmator(
            "Hapus jam kerja",
            "Apakah anda yakin ingin menghapus jam kerja ini?",
            "Ya, hapus!",
            () => {
                const { currentUrl, token } = this.state;
                const url = `${currentUrl}/${id}`;
                const payload = {
                    _token: token,
                    _method: "DELETE",
                };
                $.ajax({
                    url,
                    method: "POST",
                    data: payload,
                    success: (res) => {
                        if (res.status === "success") {
                            Swal.fire(
                                "Berhasil",
                                "Jam kerja berhasil dihapus",
                                "success"
                            );
                            window.location.reload();
                        } else {
                            Swal.fire(
                                "Gagal",
                                "Jam kerja gagal dihapus",
                                "error"
                            );
                        }
                    },
                    error: (err) => {
                        console.log(err);
                        Swal.fire("Gagal", "Jam kerja gagal dihapus", "error");
                    },
                });
            }
        );
    }


    switchStatus(id, value) {
        const { currentUrl, token } = this.state;
        const url = `${currentUrl}/${id}?update=status`;
        const payload = {
            _token: token,
            _method: "PUT",
            status: value,
        };
        $.ajax({
            url,
            method: "POST",
            data: payload,
            success: (res) => {
                // just do console
                console.log(res);
            },
            error: (err) => {
                console.log(err);
            }
        });
    }
}
