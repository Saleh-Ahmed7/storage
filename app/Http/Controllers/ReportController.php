<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StoreAction;
use PDF;
use ArPHP\I18N\Arabic;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $query = StoreAction::with('product');

        if ($from && $to) {
            // استخدام صيغة التاريخ كما يتم إرسالها من الفورم (YYYY-MM-DD)
            $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
        }

        $actions = $query->orderBy('created_at', 'desc')->get();

        $totalAdd = $actions->where('action_type', 'add')->sum('quantity_changed');
        $totalWithdraw = $actions->where('action_type', 'withdraw')->sum('quantity_changed');

        return view('report', compact('actions', 'from', 'to', 'totalAdd', 'totalWithdraw'));
    }

    public function exportPdf(Request $request)
{
    $from = $request->from;
    $to = $request->to;

    $query = StoreAction::with('product');

    // Filter by date if provided
    if ($from && $to) {
        $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
    }

    $actions = $query->orderBy('created_at', 'desc')->get();
    $all_product = Product::all();

    // Pass data to the view
    $reportHtml = view('report-pdf', [
        'actions' => $actions,
        'all_product' => $all_product
    ])->render();

    // Handle Arabic shaping
    $arabic = new Arabic();
    $p = $arabic->arIdentify($reportHtml);

    for ($i = count($p)-1; $i >= 0; $i-=2) {
        $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
        $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
    }

    $pdf = PDF::loadHTML($reportHtml);
    return $pdf->download('تقرير_المخزون.pdf');
}

}
