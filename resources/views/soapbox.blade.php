<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=no"/>
    
    <title>{{ config('app.name', '60 Second Soapbox') }}</title>

    <link href="css/app.css" rel="stylesheet">

    @if ($cordova)
        <script type="text/javascript" src="cordova.js"></script>
    @else
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    @endif
</head>
<body>
    
    <script src="js/app.js"></script>
</body>
</html>
