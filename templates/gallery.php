<!doctype html>

<head>
    <title>Профиль пользователя: {{name}} {{surname}}. Просмотр фотографий</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{galleryBack}}
{{galleryDiv}}
{{header}}
<div class="page">
    <p class="head">Профиль пользователя: <b><a style="color:#680425; text-decoration: none;" href="profile.php?id={{getId}}">{{name}} {{surname}}</a></b>. Просмотр фотографий</p>
    <br><br>
    {{uploadPhotosForm}}
    <div>
        {{photos}}
        <div class="clear"></div>
    </div>
</div>
{{footer}}
</body>

</html>
