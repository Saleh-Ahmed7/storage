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
        border: 1px solid black;
        padding: 1rem;
        margin: -2rem
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

 


</body>
</html>