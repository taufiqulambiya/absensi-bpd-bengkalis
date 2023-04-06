<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Settings as SettingsModel;

class Settings extends Component
{
    static $searchUrl = 'https://api.geoapify.com/v1/geocode/search?text=${searchValue}&format=json&filter=countrycode:id&apiKey=31df3da80fa84a71af42f092cb0070ff';
    static $reverseUrl = 'https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${long}&apiKey=31df3da80fa84a71af42f092cb0070ff';
    public $data = [];
    public $jatahCutis = [];
    public $search = '';
    public $searchResult = [];
    public $form = [
        'jenis_jatah' => 'jatah_cuti_tahunan',
        'jatah_cuti' => 0,
        'base_lat' => 0,
        'base_long' => 0,
        'base_location' => '',
    ];
    public $isLoadingMap = false;
    protected $listeners = ['mapLoaded', 'setBaseLocation'];
    public function render()
    {
        return view('livewire.admin.settings');
    }

    public function updatedFormJenisJatah()
    {
        $this->form['jatah_cuti'] = $this->data[$this->form['jenis_jatah']];
    }

    public function mount()
    {
        $this->jatahCutis = [
            'jatah_cuti_tahunan' => 'Cuti Tahunan',
            'jatah_cuti_besar' => 'Cuti Besar',
            'jatah_cuti_melahirkan' => 'Cuti Melahirkan',
            'jatah_cuti_penting' => 'Cuti Penting',
            'jatah_cuti_ctln' => 'Cuti Dengan Tanggungan Negara',
        ];
        $this->data = SettingsModel::latest()->first();
        $this->form['jatah_cuti'] = $this->data->jatah_cuti_tahunan;
        $this->form['base_lat'] = $this->data->base_lat;
        $this->form['base_long'] = $this->data->base_long;
        $this->form['base_location'] = $this->getLocation($this->data->base_lat, $this->data->base_long);
        $this->isLoadingMap = true;
    }

    public function mapLoaded()
    {
        $this->isLoadingMap = false;
    }

    public function setBaseLocation($lat, $long, $location = null)
    {
        $this->form['base_lat'] = $lat;
        $this->form['base_long'] = $long;
        $this->form['base_location'] = $location ?? $this->getLocation($lat, $long);
        $this->emit('renderMap', $lat, $long, $location);
    }

    public function save()
    {
        $this->validate([
            'form.jatah_cuti' => 'required|numeric|min:1',
        ], [
                'form.jatah_cuti.required' => 'Jatah cuti tidak boleh kosong',
                'form.jatah_cuti.numeric' => 'Jatah cuti harus berupa angka',
                'form.jatah_cuti.min' => 'Jatah cuti minimal 1',
            ]);

        $settings = SettingsModel::latest()->first();
        $settings->update([
            $this->form['jenis_jatah'] => $this->form['jatah_cuti'],
        ]);
        $this->data = $settings;
        $this->form['jatah_cuti'] = $this->data[$this->form['jenis_jatah']];
        $this->emit('success', 'Jatah cuti berhasil diubah');
    }

    // search location from google map
    public function search()
    {
        $url = str_replace('${searchValue}', $this->search, self::$searchUrl);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $response = json_decode($response->getBody()->getContents(), true);

        $this->searchResult = array_map(function ($item) {
            return [
                'formatted' => $item['formatted'],
                'lat' => $item['lat'],
                'long' => $item['lon'],
            ];
        }, $response['results']);
    }

    private function getLocation($lat, $long)
    {
        $url = str_replace('${lat}', $lat, self::$reverseUrl);
        $url = str_replace('${long}', $long, $url);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $response = json_decode($response->getBody()->getContents(), true);

        return $response['features'][0]['properties']['formatted'];
    }

    public function saveLocation()
    {
        $this->validate(
            [
                'form.base_lat' => 'required|numeric',
                'form.base_long' => 'required|numeric',
            ],
            [
                'form.base_lat.required' => 'Latitude tidak boleh kosong',
                'form.base_lat.numeric' => 'Latitude harus berupa angka',
                'form.base_long.required' => 'Longitude tidak boleh kosong',
                'form.base_long.numeric' => 'Longitude harus berupa angka',
            ]
        );

        $settings = SettingsModel::latest()->first();
        $settings->update([
            'base_lat' => $this->form['base_lat'],
            'base_long' => $this->form['base_long'],
        ]);
        $this->data = $settings;
        $this->form['base_lat'] = $this->data->base_lat;
        $this->form['base_long'] = $this->data->base_long;
        $this->form['base_location'] = $this->getLocation($this->data->base_lat, $this->data->base_long);
        $this->emit('success', 'Lokasi berhasil diubah');
    }
}