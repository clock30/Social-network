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
    <p class="head"><a class="head" href="forum.php">Форум</a> <b>/</b> Пользователь <b>{{getName}} {{getSurname}}</b> <b>/</b> Тема: <b>{{theme}}</b></p>
    <br>
    <p>Уважаемый <b>{{getName}} {{getSurname}}</b>, в данном разделе можно пообщаться с другими зарегистрированными пользователями на вышеуказанную тему.</p>
    <br>
    <div class="comments_screen">
        {{deleteCommentForm}}
        {{comment}}
        <p><b>Комментарии:</b></p><br>
        {{comments}}
    </div>
</div>
{{footer}}
</body>

</html>
