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
      border: 1px solid #f6b111;
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
      text-align: center;
    }

    .btn-success {
      border: none;
      border-radius: 40px;
      font-weight: 600;
      transition: 0.3s;
    }
  .but-3 {
      color: #ffffffff;
      border-radius: 40px;
      font-weight: 600;
      transition: all 0.3s;
    }


  </style>
</head>
<body >

  <div class="container my-5">
    <h1 class="text-center mb-4 title-1">قائمة المنتجات</h1>
     <div class="d-flex justify-content-start mb-3 gap-3">
        <a href="{{ url('/add-product') }}" class="btn but-1 px-4">رجوع إلى صفحة الإضافة</a>
      </div>
@if(session('error'))
    <div id="errorAlert" class="alert alert-danger text-center">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))

    <div id="errorAlert" class="alert alert-success  text-center">
        {{ session('success') }}
    </div>
@endif


    <!-- بحث -->
    <form method="GET" action="/search-barcode" class="mb-3 d-flex justify-content-between">
<input autofocus type="text" name="search" class="form-control mx-2 search" placeholder="ابحث باسم المنتج أو امسح الباركود">
<ul id="liveResults" class="list-group position-absolute w-100" style="z-index:999;"></ul>

      <button type="submit" class="btn but-1 mx-2 px-4">بحث</button>
      <a href="{{url('/report')}}" type="submit" class="btn but-3 btn-primary px-4">التقارير</a>

    </form>
@php
  $cart = session('cart', []);
@endphp

@if(count($cart) > 0)
<form method="POST" action="{{ url('/update-all-quantities') }}">
@csrf

<h3 class="text-warning">المنتجات المختارة</h3>

<table class="mb-3">
    <thead>
        <tr>
            <th>اسم المنتج</th>
            <th>الكمية الحالية</th>
            <th>المكان</th>
            <th>الباركود</th>
            <th>العملية</th>
            <th>الكمية</th>
            <th>إلغاء</th>
        </tr>
    </thead>
    <tbody id="cartTable">
        @foreach($cart as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->location }}</td>
                <td>{!! DNS1D::getBarcodeHTML($product->barcode, 'C128') !!}</td>
                <td>
                    <select name="products[{{ $product->id }}][action_type]" required class="form-select">
                        <option value="">— اختر —</option>
                        <option value="add">إضافة</option>
                        <option value="withdraw">سحب</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="products[{{ $product->id }}][quantity_changed]" required class="form-control" placeholder="0">
                </td>
                <td>
                    <a href="{{ url('/remove-from-cart/' . $product->id) }}" class="btn btn-danger">إلغاء</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-end mb-5">
    <button type="submit" class="btn btn-success px-4">تأكيد جميع التعديلات</button>
</div>
</form>
@endif



    <!-- جدول المنتجات -->
      <h3 class="text-warning">جميع المنتجات</h3>

      <table class="mb-5">
        <thead>
          <tr>
            <th>اسم المنتج</th>
            <th>الكمية الحالية</th>
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
            <td colspan="6" class="text-center text-muted">لا توجد نتائج</td>
          </tr>
          @endforelse
        </tbody>
      </table>
  
     
    
  </div>
<script>
    setTimeout(() => {
        let alert = document.getElementById('errorAlert');
        if (alert) {
            alert.style.transition = "0.5s";
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000); // 5000 ms يعني 5 ثواني

    let input = document.querySelector('input[name="search"]');
let results = document.getElementById('liveResults');

input.addEventListener('keyup', function () {
    let value = this.value.trim();
    if (value.length < 2) {
        results.innerHTML = "";
        return;
    }

    fetch(`/search-products?search=${value}`)
        .then(res => res.json())
        .then(data => {
            results.innerHTML = "";
            data.forEach(product => {
                let item = document.createElement('li');
                item.className = "list-group-item list-group-item-action";
                item.textContent = product.product_name + " | " + product.barcode;
                item.onclick = () => addToCart(product.id);
                results.appendChild(item);
            });
        });
});

function addToCart(id) {
    fetch(`/add-to-cart`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ id })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            location.reload(); // 🔥 يعيد تحميل الصفحة لعرض المنتج في الجدول
        }
    });
}

</script>

</body>
</html>
