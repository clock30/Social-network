<!doctype html>

<head>
    <title>Поиск зарегистрированных пользователей</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{header}}
<div class="page">
    <p class="head">Поиск пользователей</p>
    <br>
    <div class="search">
        <form method="POST" action="" class="users_search">
            <label>Логин: <input type="text" name="login" value="{{echoLogin}}"></label><br><br>
            <label>Фамилия: <input type="text" name="surname" value="{{echoSurname}}"></label><br><br>
            <label>Имя: <input type="text" name="name" value="{{echoName}}"></label><br><br>
            <label>Отчество: <input type="text" name="patronymic" value="{{echoPatronymic}}"></label><br><br>
            <label>Дата рождения: <input type="date" name="birth_date" value="{{echoBirth_date}}"></label><br><br>
            <input type="submit" name="user_search" value="НАЙТИ"><br><br><a style="color: black;" href="users.php">Очистить форму и результат поиска</a>
        </form>
    </div>
    <div class="search_result">
        <p><b>Результаты поиска:</b></p>
        <br>
        {{searchResult}}
    </div>
    <div class="clear"></div>
    <p class="head">Список зарегистрированных пользователей</p>
    <div class="users_list">
        {{getUsers}}
    </div>
</div>
{{footer}}
</body>