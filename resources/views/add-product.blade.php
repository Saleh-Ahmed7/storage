<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>إضافة منتج</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: 'Tajawal', sans-serif; }
    .form-container { max-width: 500px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .btn-custom { background-color: #0d6efd; color: #fff; transition: 0.3s; }
    .btn-custom:hover { background-color: #0b5ed7; }
  </style>
</head>
<body>
  <div class="form-container">
    <h3 class="text-center mb-4">إضافة منتج جديد</h3>

    @if (session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form action="/products" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label">اسم المنتج</label>
        <input type="text" class="form-control" name="product_name" placeholder="أدخل اسم المنتج" required>
      </div>
      <div class="mb-3">
        <label class="form-label">الكمية</label>
        <input type="number" class="form-control" name="quantity" placeholder="أدخل الكمية" required>
      </div>
      <div class="mb-3">
        <label class="form-label">مكان المنتج</label>
        <input type="text" class="form-control" name="location" placeholder="أدخل مكان المنتج" required>
      </div>
      <button type="submit" class="btn btn-custom w-100">إضافة المنتج</button>
    </form>

    <a href="/all-products" class="btn btn-secondary w-100 mt-3">عرض كل المنتجات</a>
  </div>
</body>
</html>
