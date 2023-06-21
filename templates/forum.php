<!doctype html>

<head>
    <title>Пользователь {{getName}} {{getSurname}}. Форум</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{header}}
<div class="page">
    <p class="head">Форум <b>/</b> Пользователь <b>{{getName}} {{getSurname}}</b></p>
    <br>
    <p>Уважаемый <b>{{getName}} {{getSurname}}</b>, в данном разделе можно пообщаться с другими зарегистрированными пользователями сайта на различные темы. Список тем прилагается.</p>
    <br>
    {{createNewTheme}}
    <br>
    {{themeList}}
</div>
{{footer}}
</body>

</html>
