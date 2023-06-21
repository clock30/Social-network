<!doctype html>

<head>
    <title>Профиль пользователя: {{name}} {{surname}}</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{postDiv}}
{{header}}
<div class="page">
    <div>
        <p class="profile">Профиль пользователя: <b>{{login}}</b></p>{{writeMessage}}{{friendship}}{{gag}}
    </div>
    <div class="profile">
        <div>{{profilePhoto}}</div>
        <br>
        <p class="personal">Фамилия: <span><b>{{surname}}</b></span></p>
        <p class="personal">Имя: <span><b>{{name}}</b></span></p>
        <p class="personal">Отчество: <span><b>{{patronymic}}</b></span></p>
        <p class="personal">Возраст: <span><b>{{age}}</b></span> лет</p>
        <p class="personal">Email: <span><b>{{email}}</b></span></p>
        <p class="photos"><b><a style="color: #680425; text-decoration: none;" href="gallery.php?id={{getId}}">Фотографии</a></b></p>
        {{friendshipOfferList}}
    </div>
    <div class="feed">
        <div class="post_feed">
            {{writePost}}
            {{postFeed}}
        </div>
    </div>
    <div class="clear"></div>
</div>
{{footer}}
</body>

</html>
