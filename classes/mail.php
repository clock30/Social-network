<?php
class Mail extends Page{
    private int $user_one;
    //private int $user_two;
    //private string $message;
    public function __construct($user_one){
        $this->user_one = $user_one;
        //$this->user_two = $user_two;
        //$this->message = $message;
    }
    public function dialog(){ //создание диалога
        if(isset($_POST['user_two']) && isset($_POST['message_text'])) {
            $user_one = $this->user_one;
            $user_two = $_POST['user_two'];
            $message = $_POST['message_text'];
            $date = date("Y-m-d H:i:s");
            if ($user_one != $user_two) {
                // Поиск диалога
                require 'classes/link.php';
                $query = "SELECT id FROM conversation WHERE first='$user_one' AND second='$user_two' OR first='$user_two' AND second='$user_one'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                $row_conversation = mysqli_fetch_assoc($result);
                // Если диалог не создан ранее - создаем
                if (empty($row_conversation)) {//Создаем диалог
                    $query = "INSERT INTO conversation SET first='$user_one', second='$user_two', last_message_id='0', sender='$user_one', first_delete='0', second_delete='0'";
                    mysqli_query($link, $query) or die(mysqli_error($link));
                    // Получаем Id созданного диалога
                    $query = "SELECT * FROM conversation WHERE (first='$user_one' AND second='$user_two') OR (first='$user_two' AND second='$user_one')";
                    $result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $data = mysqli_fetch_assoc($result);
                    $last_conversation_id = $data['id']; //Получаем id диалога (если его не было в бд)
                }
                else {
                    $last_conversation_id = $row_conversation['id']; //Получаем id диалога (если он был в бд)
                }
                // Добавляем сообщение
                $query = "INSERT INTO messages SET conv_id='$last_conversation_id', sender='$user_one', addressee='$user_two', readed_sender='1', readed_addressee='0', sender_delete='0', addressee_delete='0', message='$message', date='$date'";
                mysqli_query($link, $query) or die(mysqli_error($link));
                $query = "SELECT * FROM messages ORDER BY id DESC"; //Получаем id последнего сообщения
                $result = mysqli_query($link, $query) or die(mysqli_error($link)); //Получаем id последнего сообщения
                for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row); //Получаем id последнего сообщения
                $last_message_id = $data[0]['id']; //Получаем id последнего сообщения

                // Перебираем сообщения из последнего диалога, чтобы прочитать кол-во непрочитанных
                $query = "SELECT * FROM messages WHERE conv_id='$last_conversation_id'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
                $user_two_unread = 0;
                foreach($data as $item) {
                    if($user_two == $item['addressee']){
                        if($item['readed_addressee']==0){
                            $user_two_unread++;
                        }
                    }
                }
                //Находим наш диалог в БД
                $query = "SELECT * FROM conversation WHERE (first='$user_one' AND second='$user_two') OR (first='$user_two' AND second='$user_one')";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                $conversation = mysqli_fetch_assoc($result);
                $first_unread = 0;
                $second_unread = 0;
                if($user_two == $conversation['first']){
                    $first_unread = $user_two_unread;
                }
                if($user_two == $conversation['second']){
                    $second_unread = $user_two_unread;
                }
                $query = "UPDATE conversation SET last_message_id='$last_message_id', first_unread='$first_unread', second_unread='$second_unread', first_delete='0', second_delete='0' WHERE id='$conversation[id]'";
                mysqli_query($link, $query) or die(mysqli_error($link));
                header("Location: mail.php?id=$_SESSION[id]&addressee=$_GET[addressee]");
            }
            else return "";
        }
        else return "";
    }

    public function allDialogsView(){ //просмотр всех диалогов пользователя
        if(isset($_GET['id']) && !isset($_GET['addressee'])) {
            $user_id = $this->user_one;
            require 'classes/link.php';
            $query = "SELECT auth.id as userId, auth.login, conversation.id as convId, conversation.sender, conversation.first, conversation.second,  conversation.first_unread, conversation.second_unread, messages.message, messages.date 
            FROM auth , conversation LEFT JOIN messages ON (conversation.last_message_id=messages.id) WHERE (conversation.first = '$user_id' OR conversation.second = '$user_id') 
            AND CASE WHEN conversation.first = '$user_id' THEN conversation.second = auth.id AND conversation.first_delete = '0' WHEN conversation.second = '$user_id' 
            THEN conversation.first = auth.id AND conversation.second_delete = '0' END ORDER BY CASE WHEN conversation.first = '$user_id' THEN conversation.first_unread WHEN second='$user_id' THEN conversation.second_unread END DESC, messages.date DESC";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;

            // Перебираем результат
            if (empty($data)) {
                $exit_data = '<br><p>Писем нет!</p>';
            } else {
                $exit_data = '
            <table class="all_dialogs_view">
                <tr style="border-bottom: 1px solid grey; vertical-align: top;">
                    <td style="color: black;">Собеседник</td>
                    <td style="color: black;">Дата последнего сообщения</td>
                    <td style="color: black;">Непрочитанные</td>
                    <td  colspan="2" style="color: black; text-align: center;">Действия</td>
                </tr>';
                foreach ($data as $item) {
                    $exit_data .= '
                    <tr>
                        <td><a class="dialog_view" style="color: black;" href="profile.php?id=' . $item['userId'] . '">' . $item['login'] . '</td>
                        <td style="text-align: right;">' . $item['date'] . '</td>';
                    $unread = "";
                    if($user_id == $item['first']){
                        if($item['first_unread']>0){
                            $unread = '<td style="text-align: right;"><b>' . $item['first_unread'] . '</b></td>';
                        }
                        else $unread = '<td style="text-align: right;">' . $item['first_unread'] . '</td>';
                    }
                    if($user_id == $item['second']){
                        if($item['second_unread']>0){
                            $unread = '<td style="text-align: right;"><b>' . $item['second_unread'] . '</b></td>';
                        }
                        else $unread = '<td style="text-align: right;">' . $item['second_unread'] . '</td>';
                    }
                    $exit_data .= $unread;
                    $exit_data .= '
                        <td><a class="dialog_view" style="color:#0d6d9f;" href="mail.php?id=' . $_SESSION['id'] . '&addressee=' . $item['userId'] . '">Просмотреть/Ответить</a></td>
                        <td><a class="dialog_view" style="color:#680425;" href="delete_conv.php?id=' . $_SESSION['id'] . '&conv_id=' . $item['convId'] . '">Удалить переписку</a></td>
                    </tr>';
                }
                $exit_data .= '</table>';
            }
            return $exit_data;
        }
        else return "";
    }

    public function viewDialogsWithOtherUser(){ //просмотр диалога с конкретным пользователем
        if(isset($_GET['addressee'])) {
            $other_user = $_GET['addressee'];
            $user_id = $this->user_one;
            if ($user_id != $other_user) {
                require 'classes/link.php';
                $query = "SELECT * FROM auth WHERE id='$other_user'"; //Получаем логин $other_user
                $result = mysqli_query($link,$query) or die(mysqli_error($link)); //Получаем логин $other_user
                $data = mysqli_fetch_assoc($result); //Получаем логин $other_user
                $other_user_login = $data['login']; //Получаем логин $other_user
                $query = "SELECT * FROM auth WHERE id='$user_id'"; //Получаем логин $user_id
                $result = mysqli_query($link,$query) or die(mysqli_error($link)); //Получаем логин $user_id
                $data = mysqli_fetch_assoc($result); //Получаем логин $user_id
                $user_login = $data['login']; //Получаем логин $user_id
                // Поиск диалога
                $query = "SELECT * FROM conversation WHERE first='$user_id' AND second='$other_user' OR first='$other_user' AND second='$user_id';";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                $conversation = $data;

                if (empty($conversation)) { // Если диалог не создан ранее
                    $exit_data = "<p>Сообщений с пользователем <b>$other_user_login</b> нет</p>";
                }
                else {
                    $query =
                        "SELECT
                            id,
                            date,
                            message,
                            sender,
                            addressee
                        FROM 
                            messages
                        WHERE
                            conv_id = '$conversation[id]'
                            AND
                            CASE
                                WHEN sender = '$user_id'
                                    THEN sender_delete = '0'
                                WHEN addressee = '$user_id'
                                    THEN addressee_delete = '0'
                            END
                        ORDER BY 
                            id
                        DESC";
                    $result = mysqli_query($link, $query) or die(mysqli_error($link));
                    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;//Получаем сообщения, не удаленные пользователем $user_id
                    if (empty($data)) {
                        $exit_data = "<p>Сообщений с пользователем <b>$other_user_login</b> нет</p>";
                    }
                    else { // Перебираем результат, выводим сообщения, не удаленные пользователем $user_id
                        $exit_data = '
                            <div class="correspondence">';
                        foreach ($data as $item) {
                            $exit_data .= '
                                            <span class="correspondence">'.$item['date'].'&nbsp;'.($item['sender'] == $user_id ? '<b>'.$user_login.'</b>' : '<b>'.$other_user_login.'</b>').'</span><br>
                                            <span class="correspondence">' . $item['message'] . '</span><br><br>';
                        }
                        $exit_data .= '</div>';
                    }
                    foreach($data as $item) { // Обновляем флаг просмотров сообщений
                        if ($user_id == $item['addressee']){
                            $query = "UPDATE LOW_PRIORITY messages SET readed_addressee = '1' WHERE conv_id = '$conversation[id]' AND id='$item[id]'";
                            mysqli_query($link, $query) or die(mysqli_error($link));
                        }
                    }
                    if($user_id == $conversation['first']){
                        $query = "UPDATE conversation SET first_unread = '0' WHERE id = '$conversation[id]'";
                        mysqli_query($link, $query) or die(mysqli_error($link));
                    }
                    if($user_id == $conversation['second']){
                        $query = "UPDATE conversation SET second_unread = '0' WHERE id = '$conversation[id]'";
                        mysqli_query($link, $query) or die(mysqli_error($link));
                    }
                }
                return $exit_data;
            }
            else return "";
        }
        else return "";
    }

    public function deleteMesage($message_id){ //удаление конкретного сообщения
        // Проверяем существование сообщения
        $user_id = $this->user_one;
        require 'classes/link.php';
        $query = "
            SELECT
                id
            FROM
                messages
            WHERE 
                id = '$message_id' AND (sender = '$user_id' OR addressee = '$user_id')";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        if (empty($data)) {
            echo 'Сообщение не существует!';
        }
        else {
            $query = "
            UPDATE
                messages
            SET
                sender_delete =
                        CASE sender
                            WHEN '$user_id'
                                THEN '1'
                            ELSE
                                sender_delete
                        END,
                addressee_delete = 
                        CASE addressee
                            WHEN '$user_id'
                                THEN '1'
                            ELSE
                                addressee_delete
                        END
            WHERE
                id = '$message_id'";
            mysqli_query($link,$query) or die(mysqli_error($link));
        }
    }

    public function deleteDialog(){ //Удаление диалога
        if(isset($_GET['conv_id'])) {
            // Проверяем существование диалога
            $user_id = $this->user_one;
            $conv_id = $_GET['conv_id'];
            require 'classes/link.php';
            $query = "
            SELECT
                *
            FROM
                conversation
            WHERE 
                id = '$conv_id' AND (first = '$user_id' OR second = '$user_id')";
            $result = mysqli_query($link, $query) or die(mysqli_erro($link));
            $data = mysqli_fetch_assoc($result);
            $conversation = $data;
            if (empty($data)) {
                return '<p>Диалог не существует!</p>';
            }
            else {
                $query = "
                    UPDATE
                        messages 
                    SET
                        sender_delete =
                                CASE sender
                                    WHEN '$user_id'
                                        THEN '1'
                                    ELSE
                                        sender_delete
                                END,
                        addressee_delete = 
                                CASE addressee
                                    WHEN '$user_id'
                                        THEN '1'
                                    ELSE
                                        addressee_delete
                                END
                    WHERE
                        conv_id = '$conv_id'";
                mysqli_query($link, $query) or die(mysqli_error($link));
                // Обновляем таблицу диалогов
                $query = "
                    UPDATE
                        conversation 
                    SET
                        first_delete =
                                CASE first
                                    WHEN '$user_id'
                                        THEN '1'
                                    ELSE
                                        first_delete
                                END,
                        second_delete = 
                                CASE second
                                    WHEN '$user_id'
                                        THEN '1'
                                    ELSE
                                        second_delete
                                END
                    WHERE
                        id = '$conv_id'";
                mysqli_query($link, $query) or die(mysqli_error($link));
                $query = "SELECT * FROM messages WHERE conv_id='$conversation[id]'";//Достаем все сообщения диалога
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
                foreach ($data as $item){//удаляем сообщение из БД, если оно удалено у sender и addressee
                    if($item['sender_delete']==1 && $item['addressee_delete']==1){
                        $query = "DELETE FROM messages WHERE id='$item[id]'";
                        mysqli_query($link,$query) or die(mysqli_error($link));
                    }
                }
                /*if($conversation['first_delete']==1 && $conversation['second_delete']==1){
                    $query = "DELETE FROM conversation WHERE id='$conversation[id]'";
                    mysqli_query($link,$query) or die(mysqli_error($link));
                }*/
                header("Location: mail.php?id=$_SESSION[id]");
            }
        }
        else return "";
    }

    public function messageForm(){
        if(isset($_GET['addressee']) && isset($_GET['id'])){
            require 'classes/link.php';
            $query = "SELECT * FROM auth WHERE id='$_GET[addressee]'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $other_user_login = $data['login'];
            $exit_data = "<p class='correspondence'><b>Переписка с пользователем <a class='correspondence' href='profile.php?id=".$_GET['addressee']."'>".$other_user_login."</a></b> <a style='color: grey; font-size: 16px;' href='mail.php?id=".$_SESSION['id']."'>/Закрыть/</a></p>
            <form method='POST' action='' class='message_form'>
            <input type='hidden' name='user_two' value='$_GET[addressee]'>
            <label class='message_form'>Написать сообщение:</label><textarea class='message_text' name='message_text' cols='100' rows='4'></textarea>
            <input type='submit' name='send_message'>
            </form>";
        }
        else $exit_data = "";
        return $exit_data;
    }
}