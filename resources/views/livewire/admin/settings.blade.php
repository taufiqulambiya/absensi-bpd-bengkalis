<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Umum</h4>
            </div>
            <form wire:submit.prevent="save">
                <div class="card-body">
                    <div class="form-group">
                        <label for="jenis_jatah">Pilih Jenis Jatah</label>
                        <select name="" id="jenis-jatah" class="form-control" wire:model="form.jenis_jatah">
                            @foreach ($jatahCutis as $key => $item)
                            <option value="{{$key}}">{{$item}}</option>
                            @endforeach
                        </select>
                        @error('form.jenis_jatah')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jatah_cuti_tahunan">Jumlah jatah {{$jatahCutis[$form['jenis_jatah']]}}
                            (Hari)</label>
                        <input type="number" class="form-control" placeholder="Masukkan angka..."
                            wire:model="form.jatah_cuti">
                        @error('form.jatah_cuti')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-3 card-title">Atur Lokasi Kantor</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h4>Anda bisa mencari lokasi atau memilih lokasi melalui peta atau mendapatkan lokasi.</h4>
                </div>
                <div class="form-group mb-3">
                    <label for="input-search">Cari Alamat</label>
                    <div class="input-group">
                        <input type="text" placeholder="Cari Alamat..." wire:model="search" class="form-control"
                            id="input-search" wire:keydown.enter="search">
                        <div class="input-group-prepend">
                            <button class="btn btn-primary" id="search-btn" wire:click="search"><i
                                    class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <span wire:loading wire:target="search">Mencari...</span>
                </div>

                <div class="mb-3">
                    @foreach ($searchResult as $item)
                    <div class="card mb-3" style="cursor: pointer"
                        wire:click="setBaseLocation({{$item['lat']}}, {{$item['long']}}, '{{$item['formatted']}}')">
                        <div class="card-body">
                            <div>{{$item['formatted']}}</div>
                            {{-- lat and long --}}
                            <div>{{$item['lat']}}, {{$item['long']}}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div id="map-wrapper">
                    <div id="map" wire:ignore></div>
                    {{-- <div id="map"
                        class="border bg-secondary mb-3 d-flex align-items-center justify-content-center">
                        <h3 class="text-center text-white">Memuat peta...</h3>
                    </div> --}}
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary btn-sm" id="get-location-btn" wire:click="$emit('getMyLocation')"
                        wire:loading.attr="disabled">
                        <i class="fa fa-map-marker"></i> Dapatkan Lokasi Saya
                    </button>
                </div>
                <div class="mb-3 form-group">
                    <label for="latitude">Latitude</label>
                    <input type="text" class="form-control" id="latitude" placeholder="Latitude" readonly
                        wire:model="form.base_lat">
                    @error ('form.base_lat')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3 form-group">
                    <label for="longitude">Longitude</label>
                    <input type="text" class="form-control" id="longitude" placeholder="Longitude" readonly
                        wire:model="form.base_long">
                    @error ('form.base_long') <span class="text-danger">{{$message}}</span> @enderror
                </div>
                <div class="mb-3 form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" class="form-control" id="lokasi" placeholder="Lokasi" readonly
                        wire:model="form.base_location">
                </div>
                <button class="btn btn-primary" id="submit-location-btn" wire:click="saveLocation"
                    wire:loading.attr="disabled">Simpan Lokasi</button>
            </div>
        </div>
    </div>
    <div class="data-geo" data-lat="{{$form['base_lat']}}" data-long="{{$form['base_long']}}"></div>
</div>