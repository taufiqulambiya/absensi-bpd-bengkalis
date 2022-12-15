<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class Printing extends Controller
{
    public function __invoke()
    {
    }

    public function print(Request $request, Fpdf $fpdf)
    {
        $data = json_decode($request->data);

        $header = ['No', 'NIP', 'Nama'];

        $fpdf->AddPage();
        $fpdf->SetFont('Arial', '', 14);
        
        $i = 1;
        foreach ($header as $item) {
            $fpdf->Cell(60, 6, $item, 'C');
            $i++;
        }
        $fpdf->Output();
        exit;
    }
}
