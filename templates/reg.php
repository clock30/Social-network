<!doctype html>

<head>
    <title>Регистрация нового пользователя</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{header}}
<div class="page">
    <form method="POST" action="">
        <h1 style="font-size: 20px">Регистрация</h1>
        <input type="hidden" name="hidden" value="1">
        <label>Введите логин: <input type="text" name="login" size="20" value="{{echoLogin}}">
            {{checkLogin}}</label><br><br>
        <label>Введите пароль: <input type="password" name="password" size="20" value="{{echoPassword}}">
            {{checkPassword}}</label><br><br>
        <label>Повторите пароль: <input type="password" name="password2" size="20" value="{{echoPassword2}}">
            {{checkConfirmPassword}}</label><br><br>
        <label>Ваше имя: <input type="text" name="name" value="{{echoName}}" size="20">{{checkName}}</label><br><br>
        <label>Ваше отчество: <input type="text" name="patronymic" value="{{echoPatronymic}}" size="30">{{checkPatronymic}}</label><br><br>
        <label>Ваша фамилия: <input type="text" name="surname" value="{{echoSurname}}" size="30">{{checkSurname}}</label><br><br>
        <label>Дата рождения: <input type="date" name="birth_date" value="{{echoBirth_date}}">{{checkBirth_date}}</label><br><br>
        <label>Электронная почта: <input type="text" name="email" size="35" value="{{echoEmail}}">
            {{checkEmail}}</label><br><br>
        <input type="submit" name="submit"><br><br>
        <p>Если Вы уже зарегистрированы, <a href="auth.php" style="color: black;">авторизируйтесь</a>.</p>
    </form>
</div>
{{footer}}
</body>


