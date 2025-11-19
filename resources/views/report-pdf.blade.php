<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>


 body {
        font-family: 'tajawal', DejaVu Sans, sans-serif;
        direction: rtl;
        text-align: right;
        unicode-bidi: embed; /* important for Arabic text shaping */
    }
.ltr {
            direction: ltr;
            text-align: left;
            font-family: 'dejavu sans';
        }

table {
    width: 100%;
    border-collapse: collapse;
    direction: rtl;   /* force RTL in tables */
    unicode-bidi: embed; /* fix Arabic text order */
}

th, td {
    border: 1px solid #000;
    padding: 6px;
    direction: rtl;
}
tr {
    direction: rtl;
}
</style>
    
</head>
<body>
<h2>تقرير العمليات</h2>

<table border="1" dir="rtl" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>اسم المنتج</th>
            <th>نوع العملية</th>
            <th>الكمية</th>
            <th>التاريخ</th>
        </tr>
    </thead>
    <tbody dir='rtl'>
        @foreach ($actions as $action)
            <tr>
                <td>{{ $action->product->product_name }}</td>
                <td>{{ $action->action_type }}</td>
                <td>{{ $action->quantity_changed }}</td>
                <td>{{ $action->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<!-- في هذا الجدول يظهر اسم المنتج مع الكمية الموجودة في المستودع :) -->
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>اسم المنتج</th>
            <th>الكمية</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($all_product as $action)
            <tr>
                <td>{{$action->product_name}}</td>
                <td>{{$action->quantity}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>