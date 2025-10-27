<?php

namespace App\Http\Controllers;
use Picqer\Barcode\BarcodeGeneratorHTML; // أو PNG

use Illuminate\Http\Request;
use App\Models\Product;

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


}
