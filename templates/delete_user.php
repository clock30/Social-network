<!doctype html>

<head>
    <title>Администратор: {{getLogin}}. Удаление пользователя</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{header}}
<div class="page">
    <p style="font-size: 20px;">Администратор: <b>{{getLogin}}</b>. Удаление пользователя</p>
    {{deleteUser}}
</div>
{{footer}}
</body>

</html>
