<script>
    const apiKey = '31df3da80fa84a71af42f092cb0070ff';
    const baseLat = `{{ $setting->base_lat }}`;
    const baseLon = `{{ $setting->base_long }}`;

    class Absensi {
        constructor(type){
            // initializer
            this.type = $('#absen-type').val();
            console.log(this.type)
            this.lat = null;
            this.long = null;
            this.jarak = null;
            this.location = null;
            this.video = document.querySelector('#stream video');
            this.canvas = document.querySelector('#stream canvas');
            this.stream = null;
            this.streamImage = null;
            this.initializeForm();

            // on click actions
            $('#btn-req-location').click(() => {
                this.getUserLocation()
            });
            $('#capture-btn').click(() => {
                this.captureImage();
            });
            $('#retake-btn').click(() => {
                this.getUserVideo();
            });
            $('#btn-rekam-masuk').click(() => {
                this.submitForm();
            });
            $('#btn-rekam-keluar').click(() => {
                this.submitForm();
            });
            $('#log-masuk-btn').click(() => {
                $('#no-data-masuk').hide();
                $('#data-masuk-before').removeClass('d-none');
            });
            $('#log-keluar-btn').click(() => {
                $('#no-data-keluar').hide();
                $('#data-keluar-before').removeClass('d-none');
            });
        }

        initializeForm = () => {
            $('#stream video').show();
            $('#stream img').hide().attr('src', '').removeClass('d-block');
            $('#stream canvas').hide();
            $('#capture-btn').show();
            $('#retake-btn').hide();
            $('#submit-btn').hide();
            $('#spinner-loading').hide();
            $('#btn-rekam-masuk').attr('disabled', 'disabled');
            $('#btn-rekam-keluar').attr('disabled', 'disabled');
        }

        getDistance($lat, $long) {
            const apiURL = `https://api.geoapify.com/v1/routing?waypoints=${$lat},${$long}|${baseLat},${baseLon}&mode=light_truck&apiKey=${apiKey}`;
            return new Promise(async(resolve, reject) => {
                try {
                    const response = await fetch(apiURL);
                    const json = await response.json();
                    const distance = json.features[0].properties.distance;
                    resolve(distance);
                } catch (error) {
                    reject(error.message);
                }
            })
        }

        getActualLocation($lat, $long) {
            const apiURL = `https://api.geoapify.com/v1/geocode/reverse?lat=${$lat}&lon=${$long}&apiKey=${apiKey}`;
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await fetch(apiURL);
                    const json = await response.json();
                    const { address_line1, address_line2 } = json.features[0].properties;
                    const location = [address_line1, address_line2].join(', ');
                    resolve(location);
                } catch (err) {
                    reject('Gagal mendapatkan lokasi.');
                }
            })
        }

        getUserLocation() {
            $('#location').text('Loading...');
            $('#distance').text('Loading...');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async pos => {
                    this.lat = pos.coords.latitude;
                    this.long = pos.coords.longitude;
                    try {
                        const [location, distance] = await Promise.all([this.getActualLocation(this.lat, this.long), this.getDistance(this.lat, this.long)]);

                        $('#location').text(location);
                        $('#distance').text(`${distance} Meter`);
                        $('.card-captures').removeClass('d-none');
                        $('#list-rekam-masuk').removeClass('d-none');
                        $('#list-rekam-keluar').removeClass('d-none');

                        this.jarak = distance;
                        this.location = location;
                        this.getUserVideo();
                    } catch (error) {
                        $('#location').addClass('text-danger').text('Gagal mendapatkan lokasi.');
                        $('#distance').addClass('text-danger').text(error);
                        showErrorAlert('Gagal mendapatkan lokasi.');
                    }
                }, () => {
                    $('#location').addClass('text-danger').text('Gagal mendapatkan lokasi.');
                    $('#distance').addClass('text-danger').text('Gagal mendapatkan lokasi.');
                    showErrorAlert('Harap izinkan fitur lokasi');
                });
            } else {
                showErrorAlert('Geolocation tidak didukung oleh browser.')
            }
        }

        getUserVideo() {
            this.initializeForm();
            if (navigator.getUserMedia) {
                const capturesContainerWidth = $('.captures-container').width();
                $('#stream video').width(capturesContainerWidth);
                const constraints = {
                    audio: false, video: {
                        width: capturesContainerWidth,
                        height: capturesContainerWidth * 2,
                    },
                }
                const onSuccess = res => {
                    this.stream = res;
                    this.canvas.width = capturesContainerWidth;
                    this.canvas.height = capturesContainerWidth * 2;
                    this.video.srcObject = res;
                };
                const onError = err => console.log(err);
                navigator.getUserMedia(constraints, onSuccess, onError);
            } else {
                showErrorAlert('Harap izinkan akses kamera.');
            }
        }

        captureImage = () => {
            const canvas = this.canvas;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(this.video, 0, 0, canvas.width, canvas.height);
            this.streamImage = canvas.toDataURL('image/jpeg');
            $('#stream video').hide();
            $('#stream img').attr('src', this.streamImage).show().addClass('d-block');
            $('#capture-btn').hide();
            $('#retake-btn').show();
            $('#submit-btn').show();
            $('#btn-rekam-masuk').removeAttr('disabled');
            $('#btn-rekam-keluar').removeAttr('disabled');
            this.stopTracks();
        }

        stopTracks = () => this.stream.getTracks().forEach(track => track.stop());

        async submitForm() {
            let payload = {
                id_user: `{{ $user->id }}`,
                id_jam: `{{ $jam_kerja->id }}`,
                tanggal: `{{ date('Y-m-d') }}`,
                _token: $('input[name=_token]').val(),
                dokumentasi: this.streamImage,
            };
            if (this.type === 'masuk') {
                payload = {
                    ...payload,
                    waktu_masuk: `{{ date('H:i:s') }}`,
                    lat_masuk: this.lat,
                    long_masuk: this.long,
                    jarak_masuk: this.jarak,
                    lokasi_masuk: this.location,
                    // total_jam: differenceJam,
                };
            } else {
                payload = {
                    ...payload,
                    waktu_keluar: `{{ date('H:i:s') }}`,
                    lat_keluar: this.lat,
                    long_keluar: this.long,
                    jarak_keluar: this.jarak,
                    lokasi_keluar: this.location,
                };
            }
            $('#btn-rekam-masuk').text('Loading...').attr('disabled', 'disabled');
            $.ajax({
                method: 'POST',
                url: `{{ route('absensi.store') }}`,
                data: payload,
                success: res => {
                    if(res?.success) {
                        showSuccessAlert(res.success, () => {
                            window.location.reload();
                        });
                    }
                },
                error: err => console.log(err),
                completed: () => {
                    $('#btn-rekam-masuk').text('Rekam Sekarang').removeAttr('disabled');
                }
            })
        }
    }
</script>