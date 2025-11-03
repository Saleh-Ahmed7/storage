<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>قائمة المنتجات</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap');

    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #222748;
      color: #fff;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    /* خلفية هندسية */
    body::before, body::after {
      content: '';
      position: absolute;
      z-index: 0;
    }

    body::before {
      top: 0;
      right: 0;
      width: 60%;
      height: 60%;
      background: radial-gradient(circle at top right, rgba(0, 123, 255, 0.25), transparent 70%);
      clip-path: polygon(100% 0, 100% 100%, 70% 70%, 40% 0);
    }

    body::after {
      bottom: 0;
      left: 0;
      width: 70%;
      height: 70%;
      background: linear-gradient(135deg, rgba(246,177,17,0.3), transparent 70%);
      clip-path: polygon(0 100%, 0 0, 30% 40%, 60% 100%);
    }

    .container {
      position: relative;
      z-index: 5;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(8px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
    }

    h1.title-1 {
      color: #f6b111;
      font-weight: 700;
      letter-spacing: 1px;
    }

    .search {
      border-radius: 40px;
      background-color: #fff;
      border: none;
      color: #333;
      padding: 10px 20px;
    }

    .search:focus {
      outline: 2px solid #f6b111;
      box-shadow: 0 0 10px #f6b11177;
    }

    .but-1 {
      background: #f6b111;
      color: #000;
      border-radius: 40px;
      font-weight: 600;
      transition: all 0.3s;
    }

    .but-1:hover {
      background: transparent;
      color: #f6b111;
      border: 2px solid #f6b111;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      overflow: hidden;
      border-radius: 16px;
      background: #fff;
      color: #000;
    }

    th {
      background-color: #f6b111;
      color: #000;
      padding: 12px;
      text-align: center;
    }

    td {
      padding: 10px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    tr:nth-child(even) {
      background-color: #f7f7f7;
    }

    tr:hover {
      background-color: #e9e9e9;
    }

    .barcode {
      margin: 5px 0;
    }

    .btn-success {
      background: #007bff;
      border: none;
      border-radius: 40px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-success:hover {
      background: #0056b3;
    }

  </style>
</head>
<body>

  <div class="container my-5">
    <h1 class="text-center mb-4 title-1">قائمة المنتجات</h1>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- بحث -->
    <form method="GET" action="/all-products" class="mb-3 d-flex justify-content-center">
      <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control me-2 search w-50" placeholder="ابحث باسم المنتج أو امسح الباركود">
      <button type="submit" class="btn but-1 px-4">بحث</button>
    </form>

    <!-- جدول المنتجات -->
    <form method="POST" action="{{ url('/update-all-quantities') }}">
      @csrf
      <table class="mb-5">
        <thead>
          <tr>
            <th>اسم المنتج</th>
            <th>الكمية الحالية</th>
            <th>المكان</th>
            <th>الباركود</th>
            <th>العملية</th>
            <th>الكمية</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
          <tr>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->location }}</td>
            <td class="barcode">{!! DNS1D::getBarcodeHTML($product->barcode, 'C128') !!}</td>
            <td>
              <select name="products[{{ $product->id }}][action_type]" class="form-select">
                <option value="">— اختر —</option>
                <option value="add">إضافة</option>
                <option value="withdraw">سحب</option>
              </select>
            </td>
            <td>
              <input type="number" name="products[{{ $product->id }}][quantity_changed]" class="form-control" placeholder="0">
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted">لا توجد نتائج</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <div class="d-flex justify-content-end gap-3">
        <a href="{{ url('/add-product') }}" class="btn but-1 px-4">رجوع إلى صفحة الإضافة</a>
        <button type="submit" class="btn btn-success px-4">تأكيد جميع التعديلات</button>
      </div>
    </form>
  </div>

</body>
</html>
