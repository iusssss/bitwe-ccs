<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('inc.navbar')
        <manage />
    </div>
    <script type="text/javascript" src="//media.twiliocdn.com/taskrouter/js/v1.20/taskrouter.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    </script>
</body>
</html>