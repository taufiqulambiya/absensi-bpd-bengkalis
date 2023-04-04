import * as yup from "yup";

export const addCutiScheme = yup.object().shape({
    jenis_cuti: yup.string().required("Jenis cuti harus dipilih"),
    bukti: yup
        .mixed()
        .test("required", "Bukti harus diupload", (value) => {
            if (value) {
                return value.length > 0;
            }
            return false;
        })
        .test(
            "filemime",
            "Bukti harus berupa file PDF, JPG, JPEG, PNG",
            (value) => {
                if (value) {
                    const ext = value.split(".").pop();
                    const allowed = ["pdf", "jpg", "jpeg", "png"];
                    return allowed.includes(ext);
                }
                return false;
            }
        ),
    keterangan: yup.string().required("Keterangan harus diisi"),
    dates: yup.array().min(1, "Tanggal harus dipilih"),
});
