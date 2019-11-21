<!DOCTYPE html>
<html>
<head>
	<title></title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
<body>
	<div id="app">
        @include('inc.navbar')
		<test />
	</div>
    <script type="text/javascript" src="//media.twiliocdn.com/taskrouter/js/v1.20/taskrouter.min.js"></script>
    <script type="text/javascript" src="//media.twiliocdn.com/sdk/js/client/v1.3/twilio.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    </script>
</body>
</html>