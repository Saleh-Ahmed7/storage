<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>جميع المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; background-color: #f8f9fa; }
        .barcode { margin: 5px 0; }
        .search{
          border-radius: 40px
        }
    </style>
</head>
<body>
<div class="container my-4 mt-5">
  <div>
    <h1 class="text-center mb-4">قائمة المنتجات</h1>
  </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- بحث باسم المنتج أو الباركود -->
    <form method="GET" action="/all-products" class="mb-3 d-flex">
        <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control me-2 search" placeholder="ابحث باسم المنتج أو امسح الباركود">
        <button type="submit" class="btn btn-primary search mx-3">بحث</button>
    </form>


    <!-- جدول المنتجات -->
    <table class="table table-bordered bg-white ">
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>الكمية</th>
                <th>المكان</th>
                <th>الباركود</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->location }}</td>
                <td class="barcode">{!! DNS1D::getBarcodeHTML($product->barcode, 'C128') !!}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted">لا توجد نتائج</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-end mb-3">
    <a href="{{ url('/add-product') }}" class="btn btn-success">
        رجوع إلى صفحة الإضافة
    </a>
</div>

</div>
</body>
</html>
