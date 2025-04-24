<?php
namespace App\Http\Controllers;

use App\Models\salemaster;
use App\Models\retails;
use App\Models\master;
use App\Models\item_sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PDFController extends Controller
{
    public function generatePDF($id)
    {
        $salemaster=salemaster::where('invoiceno','=',$id)->first();
        $mastermain=master::first();
    
        $retails=retails::where('retailercode','=',$salemaster->accountcode)->first();
        $item_sale=item_sale::where('invoiceno','=',$id)->get();
        
        // return view('print.saleprint',compact('salemaster','retails','mastermain','item_sale'));


        $htmlContent = view('print.saleprint',compact('salemaster','retails','mastermain','item_sale'))->render();
        $pdf = PDF::loadHTML($htmlContent);
        $filePath = storage_path('app/public/sample_pdf.pdf');
        $pdf->save($filePath);
        return response()->file($filePath, [
            'Content-Disposition' => 'attachment; filename="sample_pdf.pdf"',
        ]);
    }
}
