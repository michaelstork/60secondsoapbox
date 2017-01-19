<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=no"/>

        <title>{{ 'Admin | ' . config('app.name', 'Admin | 60 Second Soapbox') }}</title>

        <link href="css/app.css" rel="stylesheet">
        <link href="css/admin.css" rel="stylesheet">

        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body>
        @yield('content')
    </body>
</html>
