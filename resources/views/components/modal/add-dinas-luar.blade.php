<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Dinas Luar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="form-submit" enctype="multipart/form-data">
                    @csrf
                    <div class="border p-3 mb-3">
                        <div class="form-group">
                            <label for="id_user">Pegawai</label>
                            <select class="form-control" name="id_user" id="id_user" required>
                                <option value="">-- PILIH --</option>
                                @foreach ($users as $item)
                                <option value="{{ $item->id }}" data-user="{{ json_encode($item) }}" data-izin="{{ json_encode($item->tgl_izin) }}"
                                    data-cuti="{{ json_encode($item->tgl_cuti) }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="pegawai-info">
                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="">NIP</label>
                                    <input type="text" class="form-control" id="pegawai-nip" readonly
                                    aria-describedby="helpId" placeholder="NIP...">
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Golongan</label>
                                    <input type="text" class="form-control" id="pegawai-golongan" readonly
                                    aria-describedby="helpId" placeholder="Golongan...">
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Jabatan</label>
                                    <input type="text" class="form-control" id="pegawai-jabatan" readonly
                                        aria-describedby="helpId" placeholder="Jabatan...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border p-3 mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="mulai" id="mulai"
                                        min="{{ date('Y-m-d', strtotime('+1day')) }}" aria-describedby="mulaiId"
                                        placeholder="Tanggal Mulai..." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selesai">Tanggal Selesai</label>
                                    <input type="date" class="form-control" name="selesai" id="tgl-selesai"
                                        min="{{ date('Y-m-d', strtotime('+1day')) }}" aria-describedby="selesaiId"
                                        placeholder="Tanggal Selesai..." required>
                                </div>
                            </div>
                            <div class="col-12"><div id="disable-dates"></div></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="maksud">Maksud Perjalanan Dinas</label>
                        <input type="text" class="form-control" name="maksud" id="maksud" required
                            aria-describedby="maksudId" placeholder="Maksud Perjalanan Dinas...">
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi Dinas</label>
                        <input type="text" class="form-control" name="lokasi" id="lokasi" required
                            aria-describedby="lokasiId" placeholder="Lokasi Perjalanan Dinas (Nama Kota)...">
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Jelaskan lebih rinci tentang detail perjalanan" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="file">Surat Jalan</label>
                        <input type="file" class="form-control-file" name="file" id="file" required
                            placeholder="Surat Jalan..." aria-describedby="fileId">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" form="form-submit">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    const jabatans = JSON.parse(`<?= json_encode(DB::table('tb_bidang')->get()) ?>`);

    $('#pegawai-info').hide();
    let disabledDates = [];
    $('#id_user').change(function() {
        const selected = $(this).find('option:selected');
        const val = selected.val();
        const izin = selected.data('izin') || [];
        const cuti = selected.data('cuti') || [];
        const disabled = Array.from(new Set([...izin, ...cuti]));
        disabledDates = disabled.filter(v => new Date(v) >= new Date());
        // console.log(disabled);
        if (Array.isArray(disabledDates) && disabledDates.length > 0){
            $('#disable-dates').html(`<div class="alert alert-info">tanggal yang tidak diizinkan: ${disabledDates.map(v => moment(new Date(v)).format('DD/MM/YYYY')).join(', ')}</div>`);
        } else {
            $('#disable-dates').html(null);
        }
        $('#mulai, #tgl-selesai').val(null);
        
        const user = selected.data('user') || {};
        console.log(user);
        $('#pegawai-nip').val(user.nip);
        $('#pegawai-golongan').val(user.golongan);
        $('#pegawai-jabatan').val(user.bidang.nama);
        $('#pegawai-info').show();
        if (user === '') {
            $('#pegawai-info').hide();
        }
    });
    $('#mulai, #tgl-selesai').change(function(){
        const val = $(this).val();
        if (disabledDates.includes(val)) {
            showErrorAlert('Tanggal tidak dapat dipilih.');
            $(this).val(null);
            return;
        }
    });
    $('.btn-edit').each(function() {
        $(this).click(function() {
            const item = $(this).data('item');
            $('.modal-title').text('Edit Dinas Luar');
            $('#form-submit').attr('action', `{{ route('dinas_luar.store') }}/${item.id}`);
            $('#form-submit').prepend(`@method('PUT')`);

            $('#id_user').val(item.id_user);
            const userInfo = $(`#id_user option[value='${item[name]}']`).data('user') || {};
            console.log(userInfo);
            $('#pegawai-nip').val(item.user.nip);
            $('#pegawai-golongan').val(item.user.golongan);
            const jabatan = jabatans.find(v => v.id === parseInt(item.user.jabatan, 10))?.nama || '-';
            $('#pegawai-jabatan').val(jabatan);
            $('#pegawai-info').show();

            $('#mulai').val(item.mulai);
            console.log(item.selesai);
            $('#tgl-selesai').val(item.selesai);
            $('#maksud').val(item.maksud);
            $('#lokasi').val(item.lokasi);
            $('#keterangan').val(item.keterangan);
            $('#file').removeAttr('required')
        })
    });
    
    $('#add').on('hide.bs.modal', () => {
        $('#form-submit').trigger('reset');
        $('#form-submit').attr('action', `{{ route('dinas_luar.store') }}`);
        $('.modal-title').text('Tambah Dinas Luar');
        $('input[name=_method]').remove();
        $('#pegawai-info').hide();
        $('#file').attr('required', 'required')
    });
</script>