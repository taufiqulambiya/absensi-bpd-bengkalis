import $ from "jquery";

export const renderYupErrors = (errors) => {
    errors.inner.forEach((item) => {
        const inputEl = $(`#${item.path}`);
        inputEl.addClass("is-invalid");
        const template = `
            <div class="invalid-feedback">
                ${item.message}
            </div>
        `;
        if (inputEl.next().hasClass("invalid-feedback")) {
            inputEl.next().remove();
        }
        inputEl.after(template);
    });
};

export const formCreator = () => {
    const form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    return form;
};
