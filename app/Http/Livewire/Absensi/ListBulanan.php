<?php

namespace App\Http\Livewire\Absensi;

use App\Models\Absensi;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ListBulanan extends Component
{
    public $monthIndo = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    ];
    public $dates = [];
    public $data = [];
    public $years = [];
    public $filter = [];
    public $monthNumStr = '';
    public $isFiltered = false;
    public $filterString = '';
    public function render()
    {
        return view('livewire.absensi.list-bulanan');
    }

    public function mount()
    {
        $this->years = range(date('Y') - 3, date('Y'));
        $this->filter['tahun'] = date('Y');
        $this->filter['bulan'] = intval(date('m')) - 1;
        $this->monthNumStr = $this->getMonthNumberStr($this->filter['bulan'] + 1);
        $this->data = Absensi::getByMonthAdmin($this->filter['tahun'], $this->monthNumStr);
        $this->dates = range(1, Carbon::now()->daysInMonth);
    }

    private function getMonthNumberStr($month)
    {
        return $month < 10 ? '0' . $month : $month;
    }

    public function updated()
    {
        $monthReal = $this->monthIndo[$this->filter['bulan']];
        $this->filterString = "Bulan $monthReal Tahun {$this->filter['tahun']}";
        $this->isFiltered = true;
    }
    public function updatedFilter()
    {
        $this->monthNumStr = $this->getMonthNumberStr($this->filter['bulan'] + 1);
        $this->data = Absensi::getByMonthAdmin($this->filter['tahun'], $this->monthNumStr);
        $daysInMonth = Carbon::createFromDate($this->filter['tahun'], $this->filter['bulan'] + 1, 1)->daysInMonth;
        $this->dates = range(1, $daysInMonth);
    }

    public function clearFilter() {
        $this->filter['tahun'] = date('Y');
        $this->filter['bulan'] = intval(date('m')) - 1;
        $this->monthNumStr = $this->getMonthNumberStr($this->filter['bulan'] + 1);
        $this->isFiltered = false;
        $this->data = Absensi::getByMonthAdmin($this->filter['tahun'], $this->monthNumStr);
        $this->dates = range(1, Carbon::now()->daysInMonth);
    }

    public function detail($id)  {
        return redirect()->route('absensi.show', $id);
    }
}