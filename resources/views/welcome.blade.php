<!DOCTYPE html>
<html style="height: 100%;" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Projects - Task Manager</title>  <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>  <!-- Fonts -->
    <script src="{{ asset('js/laroute.js') }}" defer></script>
    <script src="https://embed.ravenhub.io/js/app.js"></script>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">  <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" rel="stylesheet" type="text/css">  <!-- Styles -->
    <link
            rel="stylesheet"
            href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
            integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.3.45/css/materialdesignicons.min.css">
</head>
<body>
<div id="app">
    <index></index>
</div>
</body>
</html>
<style>
    body{
        overflow-y: scroll;
        overflow-x: hidden;
        height: 100%;
    }

</style>