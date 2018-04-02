<!DOCTYPE html>
<html lang="{{config('app.locale')}}">
<head>
    {{--PC端后台--}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="user-scalable=no,width=device-width, initial-scale=1,maximum-scale=1" name="viewport">
    <meta name="format-detection" content="telephone=no"/>
    <meta content="Title" name="apple-mobile-web-app-title">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="csrf" content="{{csrf_token()}}">
    <title>首页-{{config("app.company_name")}}{{config("app.name")}}</title>
    <link rel="stylesheet" href="{{ url('css/admin.css') }}">
{{--    <link rel="stylesheet" href="{{ url('element-theme/index.css') }}">--}}
    <link href="https://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    {{--<script src="http://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>--}}
    <script src="{{ url('js/jquery.min.js') }}"></script>
    {{--<script src="http://cdn.bootcss.com/blueimp-md5/2.7.0/js/md5.min.js"></script>--}}
    <script src="{{ url('js/md5.js') }}"></script>
    {{--<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp&key=LCCBZ-UAPWU-3NGVY-4OQBH-CM45T-TMBPU"></script>--}}
    {{--<script src="http://cdn.bootcss.com/echarts/3.5.4/echarts.min.js"></script>--}}
    <script>
        window.GammaApp = {
            appName: "{{ config('app.name') }}"
        };
    </script>
</head>
<body>
<div id="app">
</div>
{{--<input type="hidden" id="admin_name" value="{{ $admin_name }}"/>--}}
<script src="{{ mix('js/manifest.js') }}?t={{time()}}"></script>
<script src="{{ mix('js/vendor.js') }}?t={{time()}}"></script>
<script src="{{ mix('js/admin.js') }}?t={{time()}}"></script>
</body>
</html>
