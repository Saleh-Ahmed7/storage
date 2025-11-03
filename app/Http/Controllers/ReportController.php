<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StoreAction;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $query = StoreAction::with('product');

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
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

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $actions = $query->orderBy('created_at', 'desc')->get();

        $pdf = \PDF::loadView('report-pdf', compact('actions', 'from', 'to'));
        return $pdf->download('تقرير_المخزون.pdf');
    }
}
