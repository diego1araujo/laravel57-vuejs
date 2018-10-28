<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper" id="app">

            @include('layouts.navbar')

            @include('layouts.sidebar')

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <router-view></router-view>

                        <vue-progress-bar></vue-progress-bar>
                    </div><!-- /.container-fluid -->
                </div><!-- /.content -->
            </div><!-- /.content-wrapper -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <div class="float-right d-none d-sm-inline">Anything you want</div>
                <strong>Copyright &copy; 2014-2018 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
            </footer>
        </div><!-- ./wrapper -->

        @auth
            <script>
                window.user = @json(auth()->user());
            </script>
        @endauth

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </body>
</html>
