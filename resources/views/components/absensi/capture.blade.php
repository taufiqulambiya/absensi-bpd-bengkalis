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

<div id="video-wrapper" class="border shadow">
    <video id="video" class="img-fluid" autoplay></video>
    <canvas id="canvas"></canvas>
</div>
<div class="capture-buttons" id="capture-buttons">
    <button class="btn btn-primary btn-sm" id="start">
        <i class="fa fa-camera"></i> Mulai
    </button>
    <button class="btn btn-success btn-sm" id="capture">
        <i class="fa fa-camera"></i> Ambil Gambar
    </button>
    <button class="btn btn-secondary btn-sm" id="cancel">
        <i class="fa fa-times"></i> Batal
    </button>
</div>

<script>
    class Capture {
        constructor () {
            this.video = document.getElementById('video');
            this.canvas = document.getElementById('canvas');
            this.context = this.canvas.getContext('2d');
            this.captureButtons = document.getElementById('capture-buttons');
            this.init();
            this.done = false;
        }

        init () {
            $('#canvas').hide();
            $('#cancel').hide();
            $('#capture').hide();
        }

        startCamera () {
            this.done = false;
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
                    this.video.srcObject = stream;
                    this.video.play();
                });
            }
        }

        capture () {
            const wrapperWidth = $('#video-wrapper').width();
            const wrapperHeight = $('#video-wrapper').height();
            this.canvas.width = wrapperWidth;
            this.canvas.height = wrapperHeight;
            this.context.drawImage(this.video, 0, 0, wrapperWidth, wrapperHeight);
            this.video.srcObject.getTracks()[0].stop();
            this.video.srcObject = null;
            this.video.pause();
            $('#video').hide();
            $('#canvas').show();
            this.done = true;
        }

        cancel () {
            this.video.srcObject.getTracks()[0].stop();
            this.video.srcObject = null;
            this.video.pause();
        }

        getCanvasURL () {
            return this.canvas.toDataURL('image/png');
        }
    }

    const capture = new Capture();

    $('#start').click(function () {
        capture.startCamera();
        $('#start').hide();
        $('#capture').show();
        $('#cancel').show();
    });

    $('#capture').click(function () {
        capture.capture();
        $('#capture').hide();
    });

    $('#cancel').click(function () {
        capture.cancel();
        $('#canvas').hide();
        $('#video').show();
        $('#start').show();
        $('#capture').hide();
        $('#cancel').hide();
    });
</script>