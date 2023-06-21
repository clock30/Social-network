<!doctype html>

<head>
    <title>Личная почта. Пользователь {{getName}} {{getSurname}}</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{header}}
<div class="page">
    <p class="head">Личная почта. Пользователь <b>{{getName}} {{getSurname}}</b></p>
    {{allDialogsView}}
    {{messageForm}}
    {{viewDialogsWithOtherUser}}
</div>
{{footer}}
</body>

</html>
