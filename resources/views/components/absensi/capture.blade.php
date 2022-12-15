<style>
    .capture-buttons {
        border-radius: 44px;
        display: flex;
        justify-content: center;
        bottom: 20px;
    }
    #video-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 14px;
    }
</style>

<div id="video-wrapper">
    <video id="video" class="mx-auto" style="width: 300px" height="500" autoplay></video>
    <canvas id="canvas" class="mx-auto" style="width: 300px" height="500" autoplay></canvas>
</div>
<div class="capture-buttons" id="capture-buttons"></div>

<script>
    class Capture {
        constructor() {
            this.stream = '';
            this.captureButtons = document.querySelector("#capture-buttons");
            this.videoEl = document.querySelector('#video');
            const videoWrapper = document.querySelector('#video-wrapper');
            this.videoWrapper = videoWrapper;
            this.videoWidth = videoWrapper.clientWidth;
            const canvas = document.querySelector('#canvas');
            const ctx = canvas.getContext('2d');
            this.canvas = canvas;
            this.ctx = ctx;
            this.done = false;
        }

        buttons = {
            pre: `<button class="btn btn-primary btn-sm" id="btn-start">
                    <span class="fas fa-camera"></span> Mulai Kamera
                </button>`,
            ready: `<button class="btn btn-primary btn-sm" onclick="take()">
                    <i class="fas fa-camera"></i>
                    Ambil Gambar
                </button>`,
            taken: `<button class="btn btn-warning btn-sm" onclick="retake()">
                    <i class="fas fa-refresh"></i>
                    Ulangi
                </button>`,
        }

        init() {
            this.captureButtons.innerHTML = this.buttons.pre;
            $('#canvas').hide();
        }

        getCamera() {
            return new Promise((resolve, reject) => {
                navigator.mediaDevices.getUserMedia({ video: {
                    width: 300,
                    height: 500,
                }, audio: false})
                    .then((stream) => {
                        resolve(stream);
                    })
                    .catch(err => {
                        showErrorAlert('Harap izinkan akses kamera.');
                        reject(err);
                    });
            })
        }

        async startCam() {
            this.done = false;
            $('#canvas').hide();
            $('#video').show();
            try {
                const stream = await this.getCamera();
                this.stream = stream;
                this.videoEl.srcObject = stream;
                this.captureButtons.innerHTML = this.buttons.ready;
            } catch (error) {}
        }

        stopTracks() {
            this.stream.getTracks().forEach(track => track.stop());
        }

        capture() {
            try {
                this.ctx.drawImage(this.videoEl, 0, 0, 300, 500);
                $('#video').hide();
                $('#canvas').show();
                this.captureButtons.innerHTML = this.buttons.taken;
                this.done = true;
            } catch (error) {
                showErrorAlert('Gagal mengambil gambar.');
            } finally {
                this.stopTracks();
            }
        }

        getCanvasURL() {
            return this.canvas.toDataURL();
        }
    }

    const capture = new Capture();

    capture.init();

    $('#btn-start').click(() => {
        capture.startCam();
    });

    function take() {
        capture.capture();
    }

    function retake(){
        capture.startCam();
    }
</script>