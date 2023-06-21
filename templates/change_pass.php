<!doctype html>

<head>
    <title>Изменение логина и пароля</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{header}}
<div class="page">
    <br>
    <p class="head">Пользователь <b>{{Login}}</b>. Изменение логина и пароля</p>
    <br>
    <form method='POST' action=''>
        <label>Введите новый логин: <input type='text' name='login' value="{{echoLogin}}" size='20'>{{checkLogin}}</label><br><br>
        <label>Введите старый пароль: <input type='password' name='password_old' value="" size='20'>{{checkPassword_old}}</label><br><br>
        <label>Введите новый пароль: <input type='password' name='password' value="" size='20'>{{checkPassword}}</label><br><br>
        <label>Подтвердите новый пароль: <input type='password' name='confirm_password' value="" size='20'>{{checkConfirmPassword}}</label><br><br>
        <input type='submit' name="submit">
    </form>
    <br>
    <p>Изменить персональные данные можно <a href='account.php' style='color:black;'>здесь</a></p>
    <br>
    <p>Удалить аккаунт можно <a href='delete_account.php' style='color:black;'>здесь</a></p>
</div>
{{footer}}
</body>


