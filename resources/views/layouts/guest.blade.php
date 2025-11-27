<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
      
        <!-- Scripts -->

        
<style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap');

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #222748;
            color: #fff;
            position: relative;
            overflow-x: hidden;
            align-content: center
        }

        body::before,
        body::after {
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
            background: linear-gradient(135deg, rgba(246, 177, 17, 0.3), transparent 70%);
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
            background: #919191;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            color: #000;
            transition: all 0.3s;
        }



        .btn-secondary {
            background: #007bff;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-report {
            background: #00c3ff;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-report:hover {
            background: #00c3ff93;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            transition: all 0.3s;
        }
    </style>
    </head>
    <body>
        <div class="">
            <div>
                {{-- <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a> --}}
               
            </div>

            <div >
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
