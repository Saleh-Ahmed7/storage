<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>التقرير الشهري</title>
    @include('layouts.head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #222748;
            color: white;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #f6b111, #1e90ff);
            clip-path: polygon(0 0, 100% 0, 0 100%);
            opacity: 0.25;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #1e90ff, #f6b111);
            clip-path: polygon(100% 0, 100% 100%, 0 100%);
            opacity: 0.25;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 20px;
            backdrop-filter: blur(8px);
        }

        .title-1 {
            color: #f6b111;
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
            color: #000;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #f6b111 !important;
            color: #000;
            padding: 12px;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #eaeaea;
        }

        .but-2 {
            background: #f6b111;
            color: #000;
            border-radius: 40px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .but-2:hover {
            background: transparent;
            color: #f6b111;
            border: 2px solid #f6b111;
        }
    </style>
</head>

<body>
    @include('layouts.app')

    <div class="container my-5">



        <h1 class="text-center mb-4 title-1">📊 التقرير الشهري للمخزون</h1>
        <div class="mb-3">
            <a href="{{ url('/all-products') }}" type="submit" class="btn but-1 px-4"> جميع المنتجات </a>
            <a href="{{ url('/add-product') }}" type="submit" class="btn but-1 px-4">صفحة الإضافة </a>
        </div>

        <form method="GET" action="/report" class="card mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-white">من تاريخ</label>
                    <input type="date" name="from" class="form-control" value="{{ $from }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-white">إلى تاريخ</label>
                    <input type="date" name="to" class="form-control" value="{{ $to }}">
                </div>
                <div class="col-md-4">
                    <button class="btn but-1 w-100" type="submit">عرض التقرير</button>
                </div>
            </div>
        </form>

        @if (isset($actions) && $actions->count() > 0)
            <div class="card">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>اسم المنتج</th>
                            <th>العملية</th>
                            <th>الكمية</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($actions as $a)
                            <tr>
                                {{-- @php
              dd($actions);
            @endphp --}}
                                <td>{{ $a->product->product_name ?? $a->name }}</td>
                                @php
                                    $color = '';
                                    if ($a->action_type === 'add') {
                                        $color = '#3ADE5D ';
                                    } elseif ($a->action_type === 'withdraw') {
                                        $color = '#67B2D8';
                                    } elseif ($a->action_type === 'new_product') {
                                        $color = '#FFE37A';
                                    } elseif ($a->action_type === 'deleted') {
                                        $color = '#ff00009e';
                                    }

                                @endphp

                                <td style="background-color: {{ $color }}; font-weight: 600;">
                                    {{ $a->action_type }}
                                </td>
                                <td>{{ $a->quantity_changed ?? 0 }}</td>
                                <td>{{ $a->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-center mt-4">
                    <h5 class="text-success">📈 إجمالي الكميات المضافة: {{ $totalAdd }}</h5>
                    <h5 class="text-danger">📉 إجمالي الكميات المسحوبة: {{ $totalWithdraw }}</h5>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-2">
                        <form method="POST" action="/report/pdf" class="text-center mt-3">
                            @csrf
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <button class="btn btn-success">تقرير العمليات</button>


                        </form>
                    </div>
                    <div class="col-2">
                        <form method="POST" action="{{ Route('allProductPDF') }}" class="text-center mt-3">
                            @csrf
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <button class="btn btn-success">جميع المنتجات</button>


                        </form>
                    </div>
                </div>



            </div>

            @php
                $grouped = $actions->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
                });
                $dates = $grouped->keys();
                $adds = [];
                $withdraws = [];
                foreach ($grouped as $day => $records) {
                    $adds[] = $records->where('action_type', 'add')->sum('quantity_changed');
                    $withdraws[] = $records->where('action_type', 'withdraw')->sum('quantity_changed');
                }
            @endphp

            <div class="card mt-5">
                <h4 class="text-center mb-3 title-1">📉 الرسم البياني للعمليات</h4>
                <canvas id="actionsChart" height="120"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('actionsChart');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($dates) !!},
                        datasets: [{
                                label: 'الإضافات',
                                data: {!! json_encode($adds) !!},
                                backgroundColor: 'rgba(246,177,17,0.7)',
                                borderColor: '#f6b111',
                                borderWidth: 1
                            },
                            {
                                label: 'السحوبات',
                                data: {!! json_encode($withdraws) !!},
                                backgroundColor: 'rgba(30,144,255,0.6)',
                                borderColor: '#1e90ff',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                ticks: {
                                    color: 'white'
                                }
                            },
                            y: {
                                ticks: {
                                    color: 'white'
                                },
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: 'white'
                                }
                            }
                        }
                    }
                });
            </script>
        @endif
    </div>
    @include('layouts.scripts')

</body>

</html>
