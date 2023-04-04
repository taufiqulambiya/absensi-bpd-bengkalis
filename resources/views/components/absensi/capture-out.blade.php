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


<div id="video-wrapper-out" class="border shadow">
    <video id="video-out" class="img-fluid" autoplay></video>
    <canvas id="canvas-out"></canvas>
</div>
<div class="capture-buttons-out" id="capture-buttons-out">
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
    class CaptureOut {
        constructor () {
            this.video = document.getElementById('video-out');
            this.canvas = document.getElementById('canvas-out');
            this.context = this.canvas.getContext('2d');
            this.captureButtons = document.getElementById('capture-buttons-out');
            this.init();
            this.done = false;
        }

        init () {
            $('#canvas-out').hide();
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
            const wrapperWidth = $('#video-wrapper-out').width();
            const wrapperHeight = $('#video-wrapper-out').height();
            this.canvas.width = wrapperWidth;
            this.canvas.height = wrapperHeight;
            this.context.drawImage(this.video, 0, 0, wrapperWidth, wrapperHeight);
            this.video.srcObject.getTracks()[0].stop();
            this.done = true;
            $('#canvas-out').show();
            $('#video-out').hide();
            
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

    const captureOut = new CaptureOut();

    $('#start').click(function () {
        captureOut.startCamera();
        $('#start').hide();
        $('#capture').show();
        $('#cancel').show();
    });

    $('#capture').click(function () {
        captureOut.capture();
        $('#capture').hide();
    });

    $('#cancel').click(function () {
        captureOut.cancel();
        $('#canvas-out').hide();
        $('#video-out').show();
        $('#start').show();
        $('#capture').hide();
        $('#cancel').hide();
    });
</script>