import Swal from "sweetalert2";

const MODES = ["in", "out"];
export default class AbsensiPegawai {
    constructor() {
        console.log("AbsensiPegawai");
        this.elements = {
            record: [$("#record-in .record"), $("#record-out .record")],
            noData: [$("#record-in .no-data"), $("#record-out .no-data")],
            showRecordBtn: [
                $("#record-in .show-record"),
                $("#record-out .show-record"),
            ],
            reqLocationBtn: [
                $("#record-in .location button"),
                $("#record-out .location button"),
            ],
            locationResult: [
                $("#record-in .location .result"),
                $("#record-out .location .result"),
            ],
            distance: [$("#record-in .distance"), $("#record-out .distance")],
            capture: [$("#record-in .capture"), $("#record-out .capture")],
            precapture: [
                $("#record-in .capture .precapture-box"),
                $("#record-out .capture .precapture-box"),
            ],
            btnSubmit: [
                $("#record-in .btn-submit"),
                $("#record-out .btn-submit"),
            ],
        };
        this.captureActions = {
            start: [
                $("#record-in .capture .btn-start"),
                $("#record-out .capture .btn-start"),
            ],
            capture: [
                $("#record-in .capture .btn-capture"),
                $("#record-out .capture .btn-capture"),
            ],
            retake: [
                $("#record-in .capture .btn-retake"),
                $("#record-out .capture .btn-retake"),
            ],
            finish: [
                $("#record-in .capture .btn-finish"),
                $("#record-out .capture .btn-finish"),
            ],
        };

        // data
        this.app = {};
        this.state = {};

        this.location = {
            latitude: null,
            longitude: null,
        };
        this.baseLocation = {
            latitude: 0.4587079,
            longitude: 101.3956453,
        };

        /* The above code is likely a part of a larger JavaScript codebase. It is calling a function
        called `initLivewire()` which is likely defined elsewhere in the code. The purpose of this
        function is not clear without more context, but it is likely initializing some aspect of a
        Livewire component or feature. */
        // this.initLivewire();
    }

    initLivewire() {
        window.livewire.on("getLocation", (mode) => {
            this.initReqLocation(mode);
        });
        window.livewire.on("startCapture", (mode) => {
            this.startCapture(mode);
        });
        window.livewire.on("capture", (mode) => {
            console.log("mode: ", mode);
            this.capture(mode);
        });
        window.livewire.on("setResponse", (response) => {
            const switchSwalConfig = {
                success: {
                    title: "Berhasil",
                    text: response.message,
                    icon: "success",
                },
                error: {
                    title: "Gagal",
                    text: response.message,
                    icon: "error",
                },
            };
            Swal.fire(switchSwalConfig[response.type]).then((result) => {
                window.location.reload();
            });
        });
    }

    init() {
        const dataContainer = $(".data-container");
        dataContainer.each((index, item) => {
            const data = $(item).data();
            Object.entries(data).forEach(([key, value]) => {
                this.app[key] = value;
            });
        });
        console.log(this.app);

        this.elements.showRecordBtn.forEach((item, index) => {
            item.on("click", () => {
                this.elements.record[index].removeClass("d-none");
                this.elements.noData[index].addClass("d-none");
            });
        });

        this.elements.reqLocationBtn.forEach((item, index) => {
            item.on("click", () => {
                this.initReqLocation(index);
            });
        });

        const btnSubmit = this.elements.btnSubmit;
        btnSubmit.forEach((item, index) => {
            item.on("click", (e) => {
                this.submit(index, e);
            });
        });
    }

    setState(data) {
        this.state = {
            ...this.state,
            ...data,
        };
    }

    deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    getLocation() {
        return new Promise((resolve, reject) => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const { latitude, longitude } = position.coords;
                        const data = {
                            latitude,
                            longitude,
                        };
                        resolve(data);
                    },
                    (err) => {
                        reject(err);
                    }
                );
            } else {
                reject("Geolocation is not supported by this browser.");
            }
        });
    }

    getPlaceName(location) {
        const { latitude, longitude } = location;
        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`;
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                type: "GET",
                success: (res) => {
                    resolve(res.display_name);
                },
                error: (err) => {
                    reject(err);
                },
            });
        });
    }

    getDistance(lat1, lon1, lat2, lon2) {
        const units = ["meter", "km"];
        const R = 6371; // Radius of the earth in km
        const dLat = this.deg2rad(lat2 - lat1); // deg2rad below
        const dLon = this.deg2rad(lon2 - lon1);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(this.deg2rad(lat1)) *
                Math.cos(this.deg2rad(lat2)) *
                Math.sin(dLon / 2) *
                Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const d = R * c;
        const dist = d < 1 ? [d * 1000, units[0]] : [d, units[1]];
        const formatted = dist[0].toFixed(2) + " " + dist[1];
        return formatted;
    }

    getSuffix(index) {
        return index === 0 ? "masuk" : "keluar";
    }

    async initReqLocation(index) {
        try {
            const location = await this.getLocation();
            const place = await this.getPlaceName(location);

            const distance = this.getDistance(
                this.baseLocation.latitude,
                this.baseLocation.longitude,
                location.latitude,
                location.longitude
            );
            // this.elements.distance[index].html(distance);

            // const suffix = this.getSuffix(index);
            // this.setState({
            //     [`lat_${suffix}`]: location.latitude,
            //     [`long_${suffix}`]: location.longitude,
            //     [`lokasi_${suffix}`]: place,
            //     [`jarak_${suffix}`]: distance,
            // });
            // this.initCapture(index);
            window.livewire.emit("setLocation", {
                location,
                place,
                distance,
            });
        } catch (err) {
            console.log(err);
            Swal.fire("Gagal", "Gagal mendapatkan lokasi", "error");
            // this.elements.reqLocationBtn[index].html("Dapatkan Lokasi");
        }
    }

    async startCapture(mode) {
        const captureEl = $(`#record-${mode} .capture`);
        const img = $(`#record-${mode} .capture img`);
        const videoEL = captureEl.find("video");
        const video = videoEL[0];

        img.addClass("d-none");
        videoEL.removeClass("d-none");

        // set width and height same as captureEl width and height
        video.width = captureEl.width();
        video.height = captureEl.height();

        const constraints = {
            audio: false,
            video: true,
        };

        try {
            const stream = await navigator.mediaDevices.getUserMedia(
                constraints
            );
            video.srcObject = stream;
            video.play();
            // this.captureActions.start[index].addClass("d-none");
            // this.captureActions.capture[index].removeClass("d-none");
        } catch (err) {
            console.log(err);
            Swal.fire("Gagal", "Gagal mengambil kamera", "error");
        }
    }

    capture(mode) {
        const video = $(`#record-${mode} .capture video`);
        const img = $(`#record-${mode} .capture img`);

        // create canvas element
        const canvas = document.createElement("canvas");
        canvas.width = video.width();
        canvas.height = video.height();
        const ctx = canvas.getContext("2d");
        ctx.drawImage(video[0], 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL("image/png");

        // set image src
        img.attr("src", dataURL);
        img.removeClass("d-none");
        video.addClass("d-none");

        // stop tracks
        const stream = video[0].srcObject;
        const tracks = stream.getTracks();
        tracks.forEach((track) => {
            track.stop();
        });

        window.livewire.emit("setCaptureState", "captured");
        window.livewire.emit("setCapture", dataURL);
    }

    retake(index) {
        const videoEl = this.elements.capture[index].find("video");
        const canvasEl = this.elements.capture[index].find("canvas");
        videoEl.removeClass("d-none");
        canvasEl.addClass("d-none");

        this.captureActions.retake[index].addClass("d-none");
        this.captureActions.finish[index].addClass("d-none");
        this.captureActions.capture[index].removeClass("d-none");

        this.startCapture(index);
    }

    finish(index) {
        const canvas = this.elements.capture[index].find("canvas")[0];

        this.elements.btnSubmit[index].removeClass("d-none");
        this.captureActions.finish[index].addClass("d-none");
        this.captureActions.retake[index].addClass("d-none");

        const img = canvas.toDataURL("image/png");
        const suffix = this.getSuffix(index);
        this.setState({
            [`dok_${suffix}`]: img,
        });
    }

    submit(index, e) {
        const btn = $(e.target);
        const btnSpan = btn.find("span");
        const suffix = this.getSuffix(index);
        const payload = {};
        Object.keys(this.state).forEach((key) => {
            const except = ["dok", "dok_"];
            if (!except.includes(key)) {
                payload[key] = this.state[key];
            }
        });

        if (Object.keys(this.state).includes("dok")) {
            const dok = this.state[`dok_${suffix}`];
            payload[`dok_${suffix}`] = dok;
        }

        const { currentUrl, token, jamKerja, absensiId } = this.app;
        let url = currentUrl;
        payload._token = token;
        const idJam = jamKerja?.id;
        payload.id_jam = idJam;

        if (suffix === "keluar") {
            payload._method = "PUT";
            url = `${currentUrl}/${absensiId}`;
        }

        btn.attr("disabled", true);
        btnSpan.html("Loading...");

        $.ajax({
            url,
            method: "POST",
            data: payload,
            success: (res) => {
                if (res.success) {
                    Swal.fire({
                        title: "Berhasil",
                        text: "Rekam absen berhasil",
                        icon: "success",
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire("Gagal", "Rekam absen gagal", "error");
                }
            },
            error: (err) => {
                console.log(err);
                Swal.fire("Gagal", "Rekam absen gagal", "error");
            },
            complete: () => {
                btn.attr("disabled", false);
                btnSpan.html("Submit");
            },
        });
    }
}
