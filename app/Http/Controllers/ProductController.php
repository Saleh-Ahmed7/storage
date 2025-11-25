<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDelete;
use App\Models\StoreAction;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;

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

        // توليد باركود مرقّم (بصيغة نصية من 5 أرقام)
        $lastProduct = Product::orderBy('id', 'desc')->first();
        if ($lastProduct && is_numeric($lastProduct->barcode)) {
            $lastBarcode = (int) $lastProduct->barcode;
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

        // توليد HTML للباركود لعرضه فوراً إن رغبت
        $generator = new BarcodeGeneratorHTML;
        $barcodeHtml = $generator->getBarcode($newBarcode, $generator::TYPE_CODE_128);

        // سجل الحركة في جدول store_actions
        StoreAction::create([
            'product_id' => $product->id,
            'action_type' => 'new_product',
            'quantity_changed' => $request->quantity,
        ]);

        return back()->with('success', 'تم إضافة المنتج "'.$request->product_name.'" بنجاح!')
            ->with('barcodeHtml', $barcodeHtml);
    }

    // صفحة إضافة المنتج (عرض نموذج فقط)
    public function index()
    {
        session()->forget('cart');

        // لا نحتاج جلب المنتجات هنا، فقط عرض نموذج الإضافة
        return view('add-product');
    }

    // صفحة عرض جميع المنتجات + بحث
    public function allProducts(Request $request)
    {

        $search = $request->input('search');

        $query = Product::query();

        if ($search) {
            // لو كان الإدخال كاملًا أرقام باركود أو اسم جزئي
            $query->where('product_name', 'like', '%'.$search.'%')
                ->orWhere('barcode', $search);
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        // توليد HTML للباركود لكل منتج لعرضه في الجدول
        $generator = new BarcodeGeneratorHTML;
        foreach ($products as $p) {
            $p->barcode_html = $generator->getBarcode($p->barcode, $generator::TYPE_CODE_128);
        }

        return view('all-products', compact('products', 'search'));
    }

    public function search(Request $request)
    {
        $search = $request->search;

        // ابحث بالاسم أو الباركود
        $product = Product::where('barcode', $search)
            ->orWhere('product_name', 'LIKE', "%$search%")
            ->first();

        if ($product) {
            $cart = session()->get('cart', []);

            if (! isset($cart[$product->id])) {
                $cart[$product->id] = $product;
            }

            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'تم إضافة المنتج للجدول.');
        } else {
            return back()->with('error', 'لا يوجد منتج بهذا الاسم أو الباركود.');
        }
    }

    public function addToCartAjax(Request $request)
    {
        $product = Product::where('id', $request->id)->first();

        if (! $product) {
            return response()->json(['status' => 'error']);
        }

        $cart = session()->get('cart', []);

        if (! isset($cart[$product->id])) {
            $cart[$product->id] = $product;
            session()->put('cart', $cart);
        }

        return response()->json([
            'status' => 'success',
            'cart' => $cart,
        ]);
    }

    public function liveSearch(Request $request)
    {
        $query = $request->get('q');
        $products = Product::where('product_name', 'LIKE', "%{$query}%")->get();

        return response()->json($products);
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'تم إزالة المنتج من الجدول.');
    }

    public function resteCart()
    {
        session()->forget('cart');

        return redirect()->back()->with('success', 'تم إزالة جميع المنتجات من الجدول.'); 
    }

    // تعديل كمية لمنتج واحد وتسجيل الحركة
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

        // سجل الحركة في جدول store_actions باستخدام product_id
        StoreAction::create([
            'product_id' => $product->id,
            'action_type' => $request->action_type,
            'quantity_changed' => $request->quantity_changed,
        ]);

        return back()->with('success', 'تم تحديث الكمية وتسجيل العملية بنجاح.');
        if ($request->action_type == 'withdraw') {
            if ($product->quantity < $request->quantity_changed) {
                return back()->with('error', 'الكمية غير كافية للسحب.');
            }
            $product->quantity -= $request->quantity_changed;
        }

    }

    // تحديث كميات متعددة دفعة واحدة
    public function updateAllQuantities(Request $request)
    {
        $products = $request->input('products', []);

        foreach ($products as $id => $data) {
            if (empty($data['action_type']) || empty($data['quantity_changed'])) {
                continue;
            }

            $product = Product::find($id);
            // if (!$product) continue;

            $quantityChanged = (int) $data['quantity_changed'];

            if ($data['action_type'] == 'withdraw') {
                if ($product->quantity < $quantityChanged) {
                    return back()->with('error', "الكمية غير كافية للسحب للمنتج: {$product->product_name}");

                } else {
                    $product->quantity -= $quantityChanged;
                }

            } elseif ($data['action_type'] == 'add') {
                $product->quantity += $quantityChanged;
            } else {
                continue;
            }

            $product->save();

            // سجل الحركة في جدول store_actions
            StoreAction::create([
                'product_id' => $product->id,
                'action_type' => $data['action_type'],
                'quantity_changed' => $quantityChanged,
            ]);
        }

        // 🔥 بعد التعديل احذف السلة
        session()->forget('cart');

        return back()->with('success', 'تم حفظ جميع التعديلات وتسجيل العمليات بنجاح.');
    }

    public function deleteQuantitie(Request $request)
    {
        $product = Product::find($request->id);

        // سجّل عملية الحذف
        ProductDelete::create([
            'product_id' => $product->id,
            'name' => $product->product_name,
            'action_type' => $request->action_type,

        ]);
        $product->delete();

        return back()->with('error', 'تم حذف المنتج بنجاح');

    }
}
