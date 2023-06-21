<!doctype html>

<head>
    <title>Изменение настроек пользователя</title>
    <meta charset="utf-8">
    <meta name="keywords" content="{{keywords}}">
    <meta name="description" content="{{description}}">

    <link href="style/style.css" title="style" media="screen" type="text/css" rel="stylesheet">
</head>

<body>
{{header}}
<div class="page">
    <p class="head">Пользователь <b>{{login}}</b>. Изменение персональных данных</p>
    <br>
    <form method='POST' action=''>
        <label>Имя: <input type='text' name='name' value="{{echoName}}" size='20'>{{checkName}}</label><br><br>
        <label>Отчество: <input type='text' name='patronymic' value="{{echoPatronymic}}" size='30'>{{checkPatronymic}}</label><br><br>
        <label>Фамилия: <input type='text' name='surname' value="{{echoSurname}}" size='30'>{{checkSurname}}</label><br><br>
        <label>Дата рождения: <input type='date' name='birth_date' value="{{echoBirth_date}}">{{checkBirth_date}}</label><br><br>
        <label>Email: <input type='text' name='email' value="{{echoEmail}}" size='30'>{{checkEmail}}</label><br><br>
        <input type='submit' name="submit">
    </form>
    <br>
    <p>Изменить логин или пароль можно <a href='change_pass.php' style='color:black;'>здесь</a></p>
    <br>
    <p>Удалить аккаунт можно <a href='delete_account.php' style='color:black;'>здесь</a></p>
</div>
{{footer}}
</body>


