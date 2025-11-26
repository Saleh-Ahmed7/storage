<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>


 body {
        font-family: DejaVu Sans, sans-serif;
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
    padding: 2px;
    text-align: center;
}
th {
        background-color: #f6b111;
        color:rgb(46, 45, 45)

 }
 .total{
    background-color: gray;
    color:white;
 }
.tatle-pdf{
font-size: 30px
}
</style>
     
</head>
<body>
    
  



    <p class="tatle-pdf">تقرير العمليات</p>
        <table class="table-total"  border="1" dir="rtl" cellpadding="5" cellspacing="0" width="100%">
            <td class="row-total">{{ $totalAdd }}</td>
                <td class="total" colspan="2"  class="">اجمالي المسحوب</td>
                <td class="row-total">{{ $totalWithdraw}}</td>
                <td class="total" colspan="2"  class="">اجمالي المضاف</td>
        </table>

<br>

<table class="mt-5" border="1" dir="rtl" cellpadding="5" cellspacing="0" width="100%">
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


     



</body>
</html>