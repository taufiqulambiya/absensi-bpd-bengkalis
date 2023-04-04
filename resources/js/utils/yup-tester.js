import * as yup from "yup";

export const pdfOrImage = (isEdit) => {
    return yup.mixed().test('pdfOrImage', 'File harus berupa PDF atau gambar', function (value) {
        if (isEdit) {
            return true;
        }

        const { createError, path, resolve } = this;
        const file = resolve(value);
        const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        const extension = file.name.split('.').pop();

        if (!allowedExtensions.includes(extension)) {
            return createError({
                path,
                message: 'File harus berupa PDF atau gambar',
            });
        }

        return true;
    });
};
