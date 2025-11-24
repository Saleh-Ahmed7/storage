<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>


 body {
        font-family: 'tajawal', DejaVu Sans, sans-serif;
        text-align: right;
        unicode-bidi: embed; /* important for Arabic text shaping */
    }
.ltr {
            text-align: left;
            font-family: 'dejavu sans';
        }

table {
    width: 100%;
    border-collapse: collapse;
    unicode-bidi: embed; /* fix Arabic text order */
        text-align: right;

}

th, td {
    border: 1px solid #000;
    padding: 6px;
    text-align: center;
}
th {
        background-color: #f6b111;
        color:rgb(46, 45, 45)

 }
</style>
     
</head>
<body>
<h2>تقرير العمليات</h2>

<table border="1" dir="rtl" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>التاريخ</th>
            <th>الكمية</th>
            <th>نوع العملية</th>
            <th>اسم المنتج</th>
        </tr>
    </thead>
    <tbody dir='rtl'>
        @foreach ($actions as $action)
        @php
        $color = '';
        if($action->action_type === 'add') $color = '#3ADE5D ';
        elseif($action->action_type === 'withdraw') $color = '#67B2D8';
        elseif($action->action_type === 'new_product') $color = '#FFE37A';
        elseif($action->action_type === 'deleted') $color = '#ff00009e';

    @endphp
            <tr>
                <td>{{ $action->created_at }}</td>
                <td>{{ $action->quantity_changed ?? 0 }}</td>
                <td style="background-color: {{ $color }};">{{ $action->action_type }}</td>
                <td>{{ $action->product->product_name ?? $action->name  }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<!-- في هذا الجدول يظهر اسم المنتج مع الكمية الموجودة في المستودع :) -->
<h3>الكميات الحالية</h3>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>الكمية</th>
            <th>اسم المنتج</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($all_product as $action)
            <tr>
                <td>{{$action->quantity}}</td>
                <td>{{$action->product_name}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div>
        <h5> إجمالي الكميات المضافة: {{ $totalAdd }}</h5>
        <h5> إجمالي الكميات المسحوبة: {{ strtr($totalWithdraw, '٠١٢٣٤٥٦٧٨٩', '0123456789') }}</h5>
      </div>

</body>
</html>