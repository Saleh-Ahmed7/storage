<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>إضافة منتج</title>
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
      max-width: 700px;
    }

    h1.title-1 {
      color: #f6b111;
      font-weight: 700;
      text-align: center;
    }

    label {
      color: #f6b111;
      font-weight: 600;
    }

    .form-control {
      border-radius: 40px;
      border: none;
      padding: 12px 20px;
    }

    .form-control:focus {
      outline: 2px solid #f6b111;
      box-shadow: 0 0 10px #f6b11177;
    }

    .btn-primary {
      background: #f6b111;
      border: none;
      border-radius: 40px;
      font-weight: 600;
      color: #000;
      transition: all 0.3s;
    }

    .btn-primary:hover {
      background: transparent;
      color: #f6b111;
      border: 2px solid #f6b111;
    }

    .btn-secondary {
      background: #007bff;
      border: none;
      border-radius: 40px;
      font-weight: 600;
      transition: all 0.3s;
    }

    .btn-secondary:hover {
      background: transparent;
      color: #007bff;
      border: 2px solid #007bff;
    }

  </style>
</head>
<body>

  <div class="container mt-5">
    <h1 class="mb-4 title-1">إضافة منتج جديد</h1>

    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

<form action="{{ url('/products') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="product_name" class="form-label">اسم المنتج</label>
        <input type="text" class="form-control" id="product_name" name="product_name" required>
      </div>

      <div class="mb-3">
        <label for="quantity" class="form-label">الكمية</label>
        <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
      </div>

      <div class="mb-3">
        <label for="location" class="form-label">المكان</label>
        <input type="text" class="form-control" id="location" name="location" required>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="{{ url('/all-products') }}" class="btn btn-secondary px-4">رجوع إلى جميع المنتجات</a>
        <button type="submit" class="btn btn-primary px-4">إضافة المنتج</button>
      </div>
    </form>
  </div>

</body>
</html>
