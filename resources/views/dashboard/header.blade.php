<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Homepage</title>
    <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap-rtl.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/table.css') }}"/>
    {{--    <link rel="stylesheet"--}}
    {{--          href="https://fonts.googleapis.com/css?family=Open+Sans|Public+Sans|Roboto|Source+Sans+Pro&amp;display=swap"/>--}}
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/printThis.js') }}" defer></script>
    <script type="text/javascript">
        $('.nav-item').on('click', 'a', function(e){
            $(this).siblings().remove("active");
            $(this).addClass("active");
        });
    </script>

    <style>
        .card-body {
            max-height: 300px !important;
            overflow-y: auto;
        }
        .group {
            margin: 200px;
            position: relative;
        }

        .myInput:focus::-webkit-input-placeholder {
            visibility: hidden; /*hide placeholder on focus*/
        }

        .myInput {
            padding: 5px;
        }

        .myInput:focus {
            border: 1px solid dodgerblue;
            border-radius: 3px;
        }

        .myInput-label {
            display: none;
            font-size: 11px;
            color: #5c5a5a;
            border-radius: 50px;
            font-family: sans-serif;
            background-color: #fff;
            padding: 0 3px;
        }

        ul.navbar-nav {
            padding-right: 0 !important;
        }


        /*style of label when input is focused*/
        .myInput:focus + .myInput-label {
            display: inline-block;
            position: absolute;
            left: 12px;
            top: 0px;
        }

        .navbar {
            background-color: #1b1e21 !important;
            color: #fff;
        }
        .navbar .nav-item a {
            color: #fff !important;
        }
        .navbar .nav-item .router-link-active {
            color: yellowgreen !important;
            font-weight: bold;
        }
    </style>

</head>
<body class="rtl">
<div class="page home-page" id="app">
    @include('dashboard.sidebar')

    <div class="content-area">
        <nav class="navbar navbar-expand-lg navbar-light bg-light pt-2 pb-2">
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <router-link to="/users" class="nav-link">المستخدمين</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/clients" class="nav-link">العملاء</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/suppliers" class="nav-link">الموردين</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/categories" class="nav-link">الاقسام</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/products" class="nav-link">المنتجات</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/orders" class="nav-link">المبيعات</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/reports" class="nav-link">التقارير</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/expenses" class="nav-link">المصروفات</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/settings" class="nav-link">الإعدادات</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/control" class="nav-link">متابعة المستخدمين</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/count" class="nav-link">منتجات على وشك النفاد</router-link>
                    </li>
                </ul>
                <a class="usermenu-container btn btn-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fa fa-power-off"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </nav>




