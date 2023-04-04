import $ from "jquery";
import * as yup from "yup";
import { renderYupErrors } from "../../utils/dom";

export default class Auth {
    constructor() {
        // this.init();
    }

    init() {
        const formLogin = $("#form-login");
        formLogin.on("submit", (e) => {
            this.initSubmit(e);
        });

        // for development
        $('#quick-fill').on('change', e => {
            const selected = $(e.target).val();
            $('input[name="nip"]').val(selected);
            $('input[name="password"]').val('12345');
        })
    }

    initSubmit(e) {
        e.preventDefault();
        const submitBtn = $(e.target).find("button[type=submit]");
        const serializedArray = $(e.target).serializeArray();
        const data = {};
        serializedArray.forEach((item) => {
            data[item.name] = item.value;
            const inputEl = $(`#${item.name}`);
            inputEl.on("change", () => {
                inputEl.removeClass("is-invalid");
                inputEl.next().remove();
            });
        });
        const scheme = yup.object().shape({
            nip: yup.string().min(18, "NIP harus 18 karakter").required("NIP harus diisi"),
            password: yup.string().required("Password harus diisi"),
        });
        scheme
            .validate(data, { abortEarly: false })
            .then((value) => {
                console.log(value);
                submitBtn.attr("disabled", true);
                submitBtn.html(`
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                `);
                e.target.submit();
            })
            .catch((err) => {
                renderYupErrors(err);
            });
    }
}
