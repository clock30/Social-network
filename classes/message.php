<?php
namespace classes;
class Message extends Page{//Класс Message не используется, все методы в классе Mail
    private int $user_one;
    private int $user_two;
    private string $message;
    public function __construct($user_one,$user_two,$message){
        $this->user_one = $user_one;
        $this->user_two = $user_two;
        $this->message = $message;
    }
    public function dialog(){ //создание диалога
        $user_one = $this->user_one;
        $user_two = $this->user_two;
        $message = $this->message;
        $date = date("Y-m-d H:i:s");
        if ($user_one != $user_two) {
            // Поиск диалога
            require 'classes/link.php';
            $query = "SELECT id FROM conversation WHERE first='$user_one' AND second='$user_two' OR first='$user_two' AND second='$user_one'";
            $result - mysqli_query($link,$query) or die(mysqli_error($link));
            $row_conversation = mysqli_fetch_assoc($result);
            // Если диалог не создан ранее - создаем
            if (empty($row_conversation['id'])) {
                $query = "INSERT INTO conversation SET first='$user_one', second='$user_two', last_message_id='0', sender='$user_one', first_delete='0', second_delete='0', unread='0'";
                mysqli_query($link,$query) or die(mysqli_error($link));
                // ID последнего запроса
                $query = "SELECT * FROM conversation";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                $last_conversation_id = $data['id']; //Получаем id последнего диалога (если его не было в бд)
            } else {
                $last_conversation_id = $row_conversation['id']; //Получаем id последнего диалога (если он был в бд)
            }
            // Добавляем сообщение
            $query = "INSERT INTO messages SET conv_id='$last_conversation_id', sender='$user_one', addressee='$user_two', readed='0', sender_delete='0', addressee_delete='0', message='$message', date='$date'";
            mysqli_query($link,$query) or die(mysqli_error($link));
            $query = "SELECT * FROM messages ORDER BY id DESC"; //Получаем id последнего сообщения
            $result = mysqli_query($link,$query) or die(mysqli_error($link)); //Получаем id последнего сообщения
            $data - mysqli_fetch_assoc($result); //Получаем id последнего сообщения
            $last_message_id = $data[0]; //Получаем id последнего сообщения

            $query = "SELECT COUNT(*) FROM messages WHERE conv_id='$last_conversation_id' AND readed='0' AND sender='$user_one'"; // Получаем количество непрочитанных сообщений
            $result=mysqli_query($link,$query) or die(mysqli_error($link)); // Получаем количество непрочитанных сообщений
            $count = mysqli_fetch_assoc($result); // Получаем количество непрочитанных сообщений

            // Обновляем таблицу с диалогом
            $query = "UPDATE conversation SET last_message_id='$last_message_id', sender='$user_one', unread='$count' WHERE id='$last_conversation_id'";
            mysqli_query($link,$query) or die(mysqli_error($link));
        }
        else return "";
    }

    public function allDialogsView($user_id){ //просмотр всех диалогов пользователя
        require 'classes/link.php';
        $query = "SELECT auth.id as userId, auth.login, conversation.id as convId, conversation.sender, conversation.unread, messages.message, messages.date 
        FROM auth , conversation LEFT JOIN messages ON (conversation.last_message_id=messages.id) WHERE (conversation.first = '$user_id' OR conversation.second = '$user_id') 
        AND CASE WHEN conversation.first = '$user_id' THEN conversation.second = auth.id AND conversation.first_delete = '0' WHEN conversation.second = '$user_id' 
        THEN conversation.first = auth.id AND conversation.second_delete = '0' END ORDER BY conversation.unread DESC";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);

        // Перебираем результат
        if (empty($data)) {
            echo 'Диалогов нет!';
        }
        else {
            // Перебираем результат
            echo '
        <table border="1">
            <tr>
                <td>ID диалога</td>
                <td>ID собеседника</td>
                <td>Имя собеседника</td>
                <td>Последнее сообщение</td>
                <td>Дата сообщения</td>
                <td>Непрочитанных сообщений</td>
            </tr>';
            foreach($data as $item){
                echo '
                <tr>
                    <td>'.$item['convId'].'</td>
                    <td>'.$item['userId'].'</td>
                    <td>'.$item['username'].'</td>
                    <td>'.$item['message'].'</td>
                    <td>'.$item['date'].'</td>
                    <td>'.($item['sender'] != $user_id ? $item['unread'] : 0).'</td>
                </tr>';
            }
            echo '</table>';
        }
    }

    public function viewDialogsWithOtherUser($user_id,$other_user){ //просмотр диалога с конкретным пользователем
        if ($user_id != $other_user) {
            require 'classes/link.php';
            // Поиск диалога
            $query = "SELECT id, unread FROM conversation WHERE (first='$user_id' AND second='$other_user') OR (first='$other_user' AND second='$user_id');";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $conversation_id = $data;
            // Если диалог не создан ранее
            if (empty($conversation_id)) {
                echo 'Сообщений с данным пользователем нет!';
            }
            else {
                $query =
                    "SELECT
                        id,
                        date,
                        message,
                        sender
                    FROM 
                        messages
                    WHERE
                        conv_id = '$conversation_id[id]'
                        AND
                        CASE
                            WHEN sender = '$user_id'
                                THEN sender_delete = '0'
                            WHEN addressee = '$other_user'
                                THEN addressee_delete = '0'
                        END
                    ORDER BY 
                        id
                    ASC";

                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
                if (empty($data)) {
                    echo 'Сообщений с данным пользователем нет!';
                }
                else {
                    // Перебираем результат
                    echo '
                        <table border="1">
                            <tr>
                                <td>ID сообщения</td>
                                <td>Дата</td>
                                <td>Сообщение</td>
                                <td>Статус</td>
                            </tr>';
                                foreach($data as $item) {
                                    echo '
                                    <tr>
                                        <td>'.$item['id'].'</td>
                                        <td>'.$item['date'].'</td>
                                        <td>'.$item['message'].'</td>
                                        <td>'.($item['sender'] == $user_id ? 'Отправлено' : 'Принято').'</td>
                                    </tr>';
                                }
                                echo '</table>';
                }
                if ($conversation_id['unread'] != '0') {
                    // Обновляем флаг просмотров сообщений
                    $query = "UPDATE LOW_PRIORITY messages SET readed = '1' WHERE conv_id = '$conversation_id[id]'";
                    mysqli_query($link,$query) or die(mysqli_error($link));
                    // Обновляем таблицу с диалогом
                    $query = "UPDATE LOW_PRIORITY conversation SET unread = '0' WHERE id = '$conversation_id[id]'";
                    mysqli_query($link,$query) or die(mysqli_error($link));
                }
            }
        }
    }

    public function deleteMesage($user_id,$message_id){ //удаление конкретного сообщения
        // Проверяем существование сообщения
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

    public function deleteDialog($user_id,$conv_id){ //Удаление диалога
        // Проверяем существование диалога
        require 'classes/link.php';
        $query = "
        SELECT
            id
        FROM
            conversation
        WHERE 
            id = '$conv_id' AND (first = '$user_id' OR second = '$user_id')";
        $result = mysqli_query($link,$query) or die(mysqli_erro($link));
        $data = mysqli_fetch_assoc($result);
        if (empty($data)) {
            echo 'Диалог не существует!';
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
            mysqli_query($link,$query) or die(mysqli_error($link));
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
            mysqli_query($link,$query) or die(mysqli_error($link));
        }
    }
}