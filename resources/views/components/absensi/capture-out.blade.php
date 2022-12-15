<style>
    .capture-buttons-out {
        border-radius: 44px;
        display: flex;
        justify-content: center;
        bottom: 20px;
    }
    #video-wrapper-out {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 14px;
    }
</style>

<div id="video-wrapper-out">
    <video id="video-out" style="width: 300px" height="500" autoplay></video>
    <canvas id="canvas-out" style="width: 300px" height="500" autoplay></canvas>
</div>
<div class="capture-buttons-out" id="capture-buttons-out"></div>

<script>
    class CaptureOut {
        constructor() {
            this.stream = '';
            this.captureButtons = document.querySelector("#capture-buttons-out");
            this.videoEl = document.querySelector('#video-out');
            const videoWrapper = document.querySelector('#video-wrapper-out');
            this.videoWrapper = videoWrapper;
            this.videoWidth = videoWrapper.clientWidth;
            const canvas = document.querySelector('#canvas-out');
            const ctx = canvas.getContext('2d');
            this.canvas = canvas;
            this.ctx = ctx;
            this.done = false;
        }

        buttons = {
            pre: `<button class="btn btn-primary btn-sm" id="btn-start-out">
                    <span class="fas fa-camera"></span> Mulai Kamera
                </button>`,
            ready: `<button class="btn btn-primary btn-sm" onclick="takeOut()">
                    <i class="fas fa-camera"></i>
                    Ambil Gambar
                </button>`,
            taken: `<button class="btn btn-warning btn-sm" onclick="retakeOut()">
                    <i class="fas fa-refresh"></i>
                    Ulangi
                </button>`,
        }

        init() {
            this.captureButtons.innerHTML = this.buttons.pre;
            $('#canvas-out').hide();
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
            $('#canvas-out').hide();
            $('#video-out').show();
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
                $('#video-out').hide();
                $('#canvas-out').show();
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

    const captureOut = new CaptureOut();

    captureOut.init();

    $('#btn-start-out').click(() => {
        captureOut.startCam();
    });

    function takeOut() {
        captureOut.capture();
    }

    function retakeOut(){
        captureOut.startCam();
    }
</script>