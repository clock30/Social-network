<?php
session_start();

include_once('classes/getID3-master/getid3/getid3.php');

    require 'classes/link.php';
    $id = $_SESSION['id'];
    $query = "SELECT * FROM auth WHERE id='$id'";
    $result = mysqli_query($link,$query) or die(mysqli_error($link));
    $data = mysqli_fetch_assoc($result);

    if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Добавить в предварительный просмотр') {
        if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) //Если во время загрузки файла была ошибка, эта переменная заполняется соответствующим
            // сообщением об ошибке. В случае успешной загрузки файла она содержит значение 0, которое можно сравнить с помощью константы UPLOAD_ERR_OK
        {
            // get details of the uploaded file
            $fileTmpPath = $_FILES['uploadedFile']['tmp_name']; //Временный путь, в который загружается файл, сохраняется в этой переменной
            $fileName = $_FILES['uploadedFile']['name']; //Фактическое имя файла сохраняется в этой переменной
            $fileSize = $_FILES['uploadedFile']['size']; //Указывает размер загруженного файла в байтах
            $fileType = $_FILES['uploadedFile']['type']; //Содержит mime тип загруженного файла
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps)); //В приведенном выше коде мы также выяснили расширение загруженного файла и сохранили его в переменной $fileExtension

            if(preg_match('/video\/*/',$fileType)==1) {//Проверка, является ли файл видео
                // sanitize file-name
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension; //Поскольку загруженный файл может содержать пробелы и другие специальные символы,
                // лучше очистить имя файла, и это именно то, что мы сделали в следующем шаге с помощью функции хеширования, используя в качестве соли time()

                // check if file has one of the following extensions
                $allowedfileExtensions = array('MP4', 'MOV', 'MKV', 'M4V', 'AVI', 'FLV', 'MTS', '3GP', 'M2TS', 'MPEG4', 'mp4', 'mov', 'mkv', 'm4v', 'avi', 'flv', 'mts', '3gp', 'm2ts', 'mpeg4'); //Создаем массив допустимых разрешений загружаемого файла

                if (in_array($fileExtension, $allowedfileExtensions)) //если разрешение загружаемого файла соответствует
                {
                    if (is_dir('users/' . $data['login']) === false) {
                        mkdir('users/' . $data['login']);
                    }
                    if (is_dir('users/' . $data['login'] . '/posts') === false) {
                        mkdir('users/' . $data['login'] . '/posts');
                    }
                    if (is_dir('users/' . $data['login'] . '/posts/videos') === false) {
                        mkdir('users/' . $data['login'] . '/posts/videos');
                    }
                    // directory in which the uploaded file will be moved
                    $uploadFileDir = 'users/' . $data['login'] . '/posts/videos/'; //папка, куда загружается файл
                    $dest_path = $uploadFileDir . $newFileName; //путь загрузки вместе с новым именем загружаемого файла


                    if (move_uploaded_file($fileTmpPath, $dest_path)) //Функция move_uploaded_file принимает два аргумента. Первым аргументом является имя файла загруженного файла,
                        //(временный путь) а второй аргумент - путь назначения, в который вы хотите переместить файл
                    {
                        $_SESSION['upload'] = 'Файл успешно загружен';
                        $video_resolution = getVideoResolution($dest_path);
                        $width = $video_resolution['width'];
                        $height = $video_resolution['height'];
                        global $newFileName;
                        global $height;
                        global $width;
                        $path = "/profile.php?id=$data[id]&post&upload_video_filename=$newFileName&width=$width&height=$height";
                        header("Location: $path");
                    } else {
                        $_SESSION['upload'] = 'Произошла какая-то ошибка при перемещении файла в каталог загрузки. Пожалуйста, убедитесь, что каталог загрузки доступен для записи веб-сервером';
                    }
                } else {
                    $_SESSION['upload'] = 'Не удалось загрузить файл. Разрешенные типы файлов: ' . implode(',', $allowedfileExtensions);
                }
            }
            else{ $_SESSION['upload'] = 'Загружаемый файл не является видеофайлом';
                header("Location: ../profile.php?id=$_SESSION[id]&post");
            }
        } else {
            $_SESSION['upload'] = 'При загрузке файла произошла ошибка. Пожалуйста, исправьте следующую ошибку.<br>';
            $_SESSION['upload'] .= 'Ошибка:' . $_FILES['uploadedFile']['error'];
        }
    }

function getVideoResolution($dest_path)
{
    $getID3 = new getID3;
    $file = $getID3->analyze($dest_path);
    $width = $file['video']['resolution_x'];
    $height = $file['video']['resolution_y'];

    // Размеры окна.
    if($height>$width)
    {
        $w = 0;
        $h = 500;
        if($height<=$h){
            $h=$height;
        }
    }
    else{
        $w = 718;
        $h = 0;
        if($width<=$w){
            $w=$width;
        }
    }

    if (empty($w)) {
        $w = ceil($h / ($height / $width));
    }
    if (empty($h)) {
        $h = ceil($w / ($width / $height));
    }
    if($h>500){
        $del = $w/$h;
        $h=500;
        $w=ceil($del*$h);
    }
    $resolution['width'] = $w;
    $resolution['height'] = $h;
    return $resolution;
}



/*function video($vid){
    if (file_exists($vid)) {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $vid); // check mime type
        finfo_close($finfo);

        if (preg_match('#video\/*#', $mime_type)) {

            $video_attributes = _get_video_attributes($vid, $ffmpeg_path);

            print_r('Codec: ' . $video_attributes['codec'] . '<br/>');

            print_r('Dimension: ' . $video_attributes['width'] . ' x ' . $video_attributes['height'] . ' <br/>');

            print_r('Duration: ' . $video_attributes['hours'] . ':' . $video_attributes['mins'] . ':'
                . $video_attributes['secs'] . '.' . $video_attributes['ms'] . '<br/>');

            print_r('Size:  ' . _human_filesize(filesize($vid)));

        } else {
            print_r('File is not a video.');
        }
    } else {
        print_r('File does not exist.');
    }
}

function _get_video_attributes($video, $ffmpeg) {

    $command = $ffmpeg . ' -i ' . $video . ' -vstats 2>&1';
    $output = shell_exec($command);

    $regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; // or : $regex_sizes = "/Video: ([^\r\n]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; (code from @1owk3y)
    if (preg_match($regex_sizes, $output, $regs)) {
        $codec = $regs [1] ? $regs [1] : null;
        $width = $regs [3] ? $regs [3] : null;
        $height = $regs [4] ? $regs [4] : null;
    }

    $regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
    if (preg_match($regex_duration, $output, $regs)) {
        $hours = $regs [1] ? $regs [1] : null;
        $mins = $regs [2] ? $regs [2] : null;
        $secs = $regs [3] ? $regs [3] : null;
        $ms = $regs [4] ? $regs [4] : null;
    }

    return array('codec' => $codec,
        'width' => $width,
        'height' => $height,
        'hours' => $hours,
        'mins' => $mins,
        'secs' => $secs,
        'ms' => $ms
    );
}*/
