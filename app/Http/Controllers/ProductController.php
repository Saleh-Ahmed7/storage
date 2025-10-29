<?php

namespace App\Http\Controllers;
use Picqer\Barcode\BarcodeGeneratorHTML; // أو PNG

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StoreAction;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'location' => 'required|string|max:255',

        ]);
           // توليد باركود مرقّم
    $lastProduct = Product::latest()->first();
    if ($lastProduct) {
        $lastBarcode = (int)$lastProduct->barcode;
        $newBarcode = str_pad($lastBarcode + 1, 5, '0', STR_PAD_LEFT);
    } else {
        $newBarcode = '00001';
    }

        // إنشاء المنتج
       $product = Product::create([
        'product_name' => $request->product_name,
        'quantity' => $request->quantity,
        'location' => $request->location,
        'barcode' => $newBarcode,
    ]);

// توليد صورة الباركود وحفظها (اختياري كملف PNG)
    $generator = new BarcodeGeneratorHTML();
    $barcodeHtml = $generator->getBarcode($newBarcode, $generator::TYPE_CODE_128);

    return back()->with('success', 'تم إضافة المنتج "' . $request->product_name . '" بنجاح!')
                 ->with('barcodeHtml', $barcodeHtml);

    }
    public function index()
{
    $products = Product::latest()->get(); // جلب كل المنتجات
    return view('add-product', compact('products'));
}

public function allProducts(Request $request)
{
    $search = $request->input('search');

    $query = Product::query();

    if ($search) {
        $query->where('product_name', 'like', '%' . $search . '%')
              ->orWhere('barcode', $search); // يبحث أيضًا بالباركود
    }

    $products = $query->latest()->get();

    return view('all-products', compact('products', 'search'));
}
// بحث بالباركود
public function searchByBarcode(Request $request)
{
    $barcode = $request->barcode;
    $product = Product::where('barcode', $barcode)->first();

    if ($product) {
        return view('all-products', ['products' => [$product]]);
    } else {
        return back()->with('error', 'لم يتم العثور على المنتج بالباركود.');
    }
}


public function updateQuantity(Request $request, $id)
{
    $request->validate([
        'action_type' => 'required|in:add,withdraw',
        'quantity_changed' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($id);

    if ($request->action_type == 'withdraw') {
        if ($product->quantity < $request->quantity_changed) {
            return back()->with('error', 'الكمية غير كافية للسحب.');
        }
        $product->quantity -= $request->quantity_changed;
    } else {
        $product->quantity += $request->quantity_changed;
    }

    $product->save();

    // سجل الحركة في جدول store_actions
    StoreAction::create([
        'store_id' => $product->id,
        'action_type' => $request->action_type,
        'quantity_changed' => $request->quantity_changed,
    ]);

    return back()->with('success', 'تم تحديث الكمية وتسجيل العملية بنجاح.');
}
public function updateAllQuantities(Request $request)
{
    $products = $request->input('products', []);

    foreach ($products as $id => $data) {
        if (empty($data['action_type']) || empty($data['quantity_changed'])) {
            continue;
        }

        $product = Product::find($id);
        if (!$product) continue;

        $quantityChanged = (int)$data['quantity_changed'];

        if ($data['action_type'] == 'withdraw') {
            if ($product->quantity < $quantityChanged) {
                continue; // تخطي المنتج إذا الكمية غير كافية
            }
            $product->quantity -= $quantityChanged;
        } elseif ($data['action_type'] == 'add') {
            $product->quantity += $quantityChanged;
        }

        $product->save();

        // سجل الحركة في جدول store_actions
        \App\Models\StoreAction::create([
            'store_id' => $product->id,
            'action_type' => $data['action_type'],
            'quantity_changed' => $quantityChanged,
        ]);
    }

    return back()->with('success', 'تم حفظ جميع التعديلات وتسجيل العمليات بنجاح.');
}

}
