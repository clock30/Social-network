<!doctype html>

<head>
    <title>Пользователь: {{getName}} {{getSurname}}. Страница администратора</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{header}}
<div class="page">
    <p class="head">Пользователь: <b>{{getName}} {{getSurname}}</b>. Страница администратора. Список зарегистрированных пользователей сайта</p>
    <br>
    {{getAdminPanel}}
</div>
{{footer}}
</body>

</html>
