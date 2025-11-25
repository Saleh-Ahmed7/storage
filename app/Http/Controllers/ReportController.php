<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDelete;
use App\Models\StoreAction;
use ArPHP\I18N\Arabic;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        session()->forget('cart');

        $from = $request->from;
        $to = $request->to;

        $storeActions = StoreAction::with('product')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59']);
            })
            ->get();
        $productDeletes = ProductDelete::when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59']);
        })
            ->get();
        // دمج الـ Collections
        $actions = $storeActions->merge($productDeletes);

        // ترتيب حسب created_at
        $actions = $actions->sortByDesc('created_at')->values();

        if ($from && $to) {
            // استخدام صيغة التاريخ كما يتم إرسالها من الفورم (YYYY-MM-DD)
            $actions->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59']);

        }

        // $actions = $report_action->orderBy('created_at', 'desc')->get();

        $totalAdd = $actions->where('action_type', 'add')->sum('quantity_changed');
        $totalAdd += $actions->where('action_type', 'new_product')->sum('quantity_changed');
        $totalWithdraw = $actions->where('action_type', 'withdraw')->sum('quantity_changed');

        return view('report', compact('actions', 'from', 'to', 'totalAdd', 'totalWithdraw'));
    }

    public function exportPdf(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

       $storeActions = StoreAction::with('product')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59']);
            })
            ->get();
        $productDeletes = ProductDelete::when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59']);
        })
            ->get();
        // دمج الـ Collections
        $actions = $storeActions->merge($productDeletes);

        // ترتيب حسب created_at
        $actions = $actions->sortByDesc('created_at')->values();

        $totalAdd = $actions->where('action_type', 'add')->sum('quantity_changed');
        $totalAdd += $actions->where('action_type', 'new_product')->sum('quantity_changed');
        $totalWithdraw = $actions->where('action_type', 'withdraw')->sum('quantity_changed');

        // Filter by date if provided
        if ($from && $to) {
            $actions->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59']);
        }

        $all_product = Product::all();

        // Pass data to the view
        $reportHtml = view('report-pdf', [
            'actions' => $actions,
            'all_product' => $all_product,
            'totalAdd' => $totalAdd,
            'totalWithdraw' => $totalWithdraw,

        ])->render();

        // Handle Arabic shaping
        $arabic = new Arabic;
        $p = $arabic->arIdentify($reportHtml);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i - 1], $p[$i] - $p[$i - 1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }

        $pdf = PDF::loadHTML($reportHtml);

        return $pdf->download('تقرير العمليات.pdf');
    }
     public function allProductPDF(Request $request)
    {
        // ترتيب حسب created_at
        $all_product = Product::all();

        // Pass data to the view
        $reportHtml = view('allproduct-pdf', [
            'all_product' => $all_product,

        ])->render();

        // Handle Arabic shaping
        $arabic = new Arabic;
        $p = $arabic->arIdentify($reportHtml);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i - 1], $p[$i] - $p[$i - 1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }

        $pdf = PDF::loadHTML($reportHtml);

        return $pdf->download('تقرير_المخزون.pdf');
    }
}
