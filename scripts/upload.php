<?php
session_start();

require 'classes/link.php';
$id = $_SESSION['id'];
$query = "SELECT * FROM auth WHERE id='$id'";
$result = mysqli_query($link,$query) or die(mysqli_error($link));
$data = mysqli_fetch_assoc($result);
$message = '';
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Загрузить')
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

        // sanitize file-name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension; //Поскольку загруженный файл может содержать пробелы и другие специальные символы,
        // лучше очистить имя файла, и это именно то, что мы сделали в следующем шаге с помощью функции хеширования, используя в качестве соли time()

        // check if file has one of the following extensions
        $allowedfileExtensions = array('jpg', 'jpeg', 'gif', 'png'); //Создаем массив допустимых разрешений загружаемого файла

        if (in_array($fileExtension, $allowedfileExtensions)) //если разрешение загружаемого файла соответствует
        {
            if(is_dir('users/'.$data['login'])===false){
                mkdir('users/'.$data['login']);
            }
            if(is_dir('users/'.$data['login'].'/photos')===false){
                mkdir('users/'.$data['login'].'/photos');
            }
            // directory in which the uploaded file will be moved
            $uploadFileDir = 'users/'.$data['login'].'/photos/'; //папка, куда загружается файл
            $dest_path = $uploadFileDir . $newFileName; //путь загрузки вместе с новым именем загружаемого файла


            if(move_uploaded_file($fileTmpPath, $dest_path)) //Функция move_uploaded_file принимает два аргумента. Первым аргументом является имя файла загруженного файла,
                //(временный путь) а второй аргумент - путь назначения, в который вы хотите переместить файл
            {
                $message ='Файл успешно загружен';
                getIcon($dest_path,$newFileName);
                getPhoto($dest_path,$newFileName);
            }
            else
            {
                $message = 'Произошла какая-то ошибка при перемещении файла в каталог загрузки. Пожалуйста, убедитесь, что каталог загрузки доступен для записи веб-сервером';
            }
        }
        else
        {
            $message = 'Не удалось загрузить файл. Разрешенные типы файлов: ' . implode(',', $allowedfileExtensions);
        }
    }
    else
    {
        $message = 'При загрузке файла произошла ошибка. Пожалуйста, исправьте следующую ошибку.<br>';
        $message .= 'Ошибка:' . $_FILES['uploadedFile']['error'];
    }
}

function getIcon($dest_path,$newFileName)
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
        $w = 200;
        $h = 0;
    }
    else{
        $w = 0;
        $h = 200;
    }

    if (empty($w)) {
        $w = ceil($h / ($height / $width));
    }
    if (empty($h)) {
        $h = ceil($w / ($width / $height));
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

    if(is_dir('users/'.$data['login'].'/temp/')===false){
        mkdir('users/'.$data['login'].'/temp/');
    }
    $src_temp = 'users/'.$data['login'].'/temp/';

    switch ($type) {
        case 1:
            imageGif($img, $src_temp . $newFileName);
            break;
        case 2:
            imageJpeg($img, $src_temp . $newFileName, 100);
            break;
        case 3:
            imagePng($img, $src_temp . $newFileName);
            break;
    }

    imagedestroy($img);

    $info = getimagesize($src_temp.$newFileName);
    $width = $info[0];
    $height = $info[1];
    $type = $info[2];

    switch ($type) {
        case 1:
            $img = imageCreateFromGif($src_temp.$newFileName);
            imageSaveAlpha($img, true);
            break;
        case 2:
            $img = imagecreatefromjpeg($src_temp.$newFileName);
            break;
        case 3:
            $img = imageCreateFromPng($src_temp.$newFileName);
            imageSaveAlpha($img, true);
            break;
    }

    $w = 200;
    $h = 200;
    $x = '50%';
    $y = '50%';

    if (strpos($x, '%') !== false) {
        $x = intval($x);
        $x = ceil(($width * $x / 100) - ($w / 100 * $x));
    }
    if (strpos($y, '%') !== false) {
        $y = intval($y);
        $y = ceil(($height * $y / 100) - ($h / 100 * $y));
    }

    $tmp = imageCreateTrueColor($w, $h);
    if ($type == 1 || $type == 3) {
        imagealphablending($tmp, true);
        imageSaveAlpha($tmp, true);
        $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
        imagefill($tmp, 0, 0, $transparent);
        imagecolortransparent($tmp, $transparent);
    }

    imageCopyResampled($tmp, $img, 0, 0, $x, $y, $width, $height, $width, $height);
    $img = $tmp;

    if(is_dir('users/'.$data['login'].'/icons')===false){
        mkdir('users/'.$data['login'].'/icons');
    }
    $src_output = 'users/'.$data['login'].'/icons/';

    switch ($type) {
        case 1:
            imageGif($img, $src_output . $newFileName);
            break;
        case 2:
            imageJpeg($img, $src_output .  $newFileName, 100);
            break;
        case 3:
            imagePng($img, $src_output .  $newFileName);
            break;
    }

    imagedestroy($img);
    unlink($src_temp.$newFileName);
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
        $w = 2000;
        $h = 0;
        if($width<=$w){
            $w=$width;
        }
    }
    else{
        $w = 0;
        $h = 2000;
        if($height<=$h){
            $h=$height;
        }
    }

    if (empty($w)) {
        $w = ceil($h / ($height / $width));
    }
    if (empty($h)) {
        $h = ceil($w / ($width / $height));
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
    unlink('users/'.$data['login'].'/photos/'.$newFileName);

    $src = 'users/'.$data['login'].'/photos/';

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

$_SESSION['upload'] = $message;
$path = '/gallery.php?id='.$data['id'];
header("Location: $path");