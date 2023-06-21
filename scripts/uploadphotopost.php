<?php
session_start();

require 'classes/link.php';
$id = $_SESSION['id'];
$query = "SELECT * FROM auth WHERE id='$id'";
$result = mysqli_query($link,$query) or die(mysqli_error($link));
$data = mysqli_fetch_assoc($result);
$message = '';
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Добавить в предварительный просмотр')
{
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

        if (preg_match('/image\/*/', $fileType) == 1) {//Проверка, является ли файл видео
            // sanitize file-name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension; //Поскольку загруженный файл может содержать пробелы и другие специальные символы,
            // лучше очистить имя файла, и это именно то, что мы сделали в следующем шаге с помощью функции хеширования, используя в качестве соли time()

            // check if file has one of the following extensions
            $allowedfileExtensions = array('jpg', 'jpeg', 'gif', 'png'); //Создаем массив допустимых разрешений загружаемого файла

            if (in_array($fileExtension, $allowedfileExtensions)) //если разрешение загружаемого файла соответствует
            {
                if (is_dir('users/' . $data['login']) === false) {
                    mkdir('users/' . $data['login']);
                }
                if (is_dir('users/' . $data['login'] . '/posts') === false) {
                    mkdir('users/' . $data['login'] . '/posts');
                }
                if (is_dir('users/' . $data['login'] . '/posts/photos') === false) {
                    mkdir('users/' . $data['login'] . '/posts/photos');
                }
                if (is_dir('users/' . $data['login'] . '/posts/photos/temp') === false) {
                    mkdir('users/' . $data['login'] . '/posts/photos/temp');
                }
                // directory in which the uploaded file will be moved
                $uploadFileDir = 'users/' . $data['login'] . '/posts/photos/temp/'; //папка, куда загружается файл
                $dest_path = $uploadFileDir . $newFileName; //путь загрузки вместе с новым именем загружаемого файла


                if (move_uploaded_file($fileTmpPath, $dest_path)) //Функция move_uploaded_file принимает два аргумента. Первым аргументом является имя файла загруженного файла,
                    //(временный путь) а второй аргумент - путь назначения, в который вы хотите переместить файл
                {
                    getPhoto($dest_path, $newFileName);
                    $_SESSION['upload'] = 'Файл успешно загружен';
                    global $newFileName;
                    $path = "/profile.php?id=$data[id]&post&upload_photo_filename=$newFileName";
                    header("Location: $path");
                } else {
                    $_SESSION['upload'] = 'Произошла какая-то ошибка при перемещении файла в каталог загрузки. Пожалуйста, убедитесь, что каталог загрузки доступен для записи веб-сервером';
                }
            } else {
                $_SESSION['upload'] = 'Не удалось загрузить файл. Разрешенные типы файлов: ' . implode(',', $allowedfileExtensions);
            }
        }
        else { $_SESSION['upload'] = 'Загружаемый файл не является фотографией';
            header("Location: ../profile.php?id=$_SESSION[id]&post");
        }
    }
    else
    {
        $_SESSION['upload'] = 'При загрузке файла произошла ошибка. Пожалуйста, исправьте следующую ошибку.<br>';
        $_SESSION['upload'] .= 'Ошибка:' . $_FILES['uploadedFile']['error'];
    }
}

function getPhoto($dest_path,$newFileName)
{
    require 'classes/link.php';
    $id = $_SESSION['id'];
    $query = "SELECT * FROM auth WHERE id='$id'";
    $result = mysqli_query($link,$query) or die(mysqli_error($link));
    $data = mysqli_fetch_assoc($result);

    $info = getimagesize($dest_path);
    $width = $info[0];
    $height = $info[1];
    $type = $info[2];

    switch ($type) {
        case 1:
            $img = imageCreateFromGif($dest_path);
            imageSaveAlpha($img, true);
            break;
        case 2:
            $img = imagecreatefromjpeg($dest_path);
            $exif = exif_read_data($dest_path);
            if ($img && $exif && isset($exif['Orientation'])) {
                $ort = $exif['Orientation'];

                if ($ort == 6 || $ort == 5) {
                    $img = imagerotate($img, 270, 0);
                    $width = $info[1];
                    $height = $info[0];
                }
                if ($ort == 3 || $ort == 4) {
                    $img = imagerotate($img, 180, 0);
                }
                if ($ort == 8 || $ort == 7) {
                    $img = imagerotate($img, 90, 0);
                    $width = $info[1];
                    $height = $info[0];
                }

                if ($ort == 5 || $ort == 4 || $ort == 7) {
                    imageflip($img, IMG_FLIP_HORIZONTAL);
                }
            }
            break;
        case 3:
            $img = imageCreateFromPng($dest_path);
            imageSaveAlpha($img, true);
            break;
    }

    // Размеры новой фотки.
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

    $tmp = imageCreateTrueColor($w, $h);
    if ($type == 1 || $type == 3) {
        imagealphablending($tmp, true);
        imageSaveAlpha($tmp, true);
        $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
        imagefill($tmp, 0, 0, $transparent);
        imagecolortransparent($tmp, $transparent);
    }

    $tw = ceil($h / ($height / $width));
    $th = ceil($w / ($width / $height));
    if ($tw < $w) {
        imageCopyResampled($tmp, $img, ceil(($w - $tw) / 2), 0, 0, 0, $tw, $h, $width, $height);
    } else {
        imageCopyResampled($tmp, $img, 0, ceil(($h - $th) / 2), 0, 0, $w, $th, $width, $height);
    }

    $img = $tmp;
    unlink('users/'.$data['login'].'/posts/photos/temp/'.$newFileName);

    $src = 'users/'.$data['login'].'/posts/photos/temp/';

    switch ($type) {
        case 1:
            imageGif($img, $src . $newFileName);
            break;
        case 2:
            imageJpeg($img, $src . $newFileName, 100);
            break;
        case 3:
            imagePng($img, $src . $newFileName);
            break;
    }
    imagedestroy($img);
}