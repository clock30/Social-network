<!doctype html>

<head>
    <title>Пользователь: {{getLogin}}. Удаление аккаунта</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{header}}
<div class="page">
    <p>Уважаемый <b>{{getLogin}}</b>, если Вы желаете удалить аккаунт, введите пароль:</p>
    <br>
    <form method="POST" action="">
        <label>Введите пароль: <input type="password" name="password" value="{{echoPasswordDeleteAccount}}" size="20">{{checkPasswordDeleteAccount}}</label><br><br>
        <label>Подтвердите пароль: <input type="password" name="confirm_password" size="20">{{checkConfirmPasswordDeleteAccount}}</label><br><br>
        <input type="submit" name="submit">
    </form>
</div>
{{footer}}
</body>

</html>
