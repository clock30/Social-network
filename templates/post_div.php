<div class='post'></div>
<div class="post_div">
    <div class="post_div_container">
        <div class="view_post">
            <div class="post_elements">
                <p class="post_elements">Добавить новый элемент:</p>
                <form class="post_elements" method="POST" action=""><input class="post_elements" type="submit" name="head" value="Заголовок"></form>
                <form class="post_elements" method="POST" action=""><input class="post_elements" type="submit" name="subhead" value="Подзаголовок"></form>
                <form class="post_elements" method="POST" action=""><input class="post_elements" type="submit" name="text" value="Текстовый блок"></form>
                <form class="post_elements" method="POST" action=""><input class="post_elements" type="submit" name="link" value="Ссылка"></form>
                <form class="post_elements" method="POST" action=""><input class="post_elements" type="submit" name="article" value="Новый абзац"></form>
                <form class="post_elements" method="POST" action=""><input class="post_elements" type="submit" name="br" value="Новая строка"></form>
                <form class="post_elements" method="POST" action=""><input class="post_elements" type="submit" name="hr" value="Разделитель"></form>
                <form class="post_elements" method="POST" action=""><input class="post_elements" type="submit" name="photo" value="Фотография"></form>
                <form class="post_elements" method="POST" action=""><input class="post_elements" type="submit" name="video" value="Видео"></form>
            </div>
            <div class="article"></div>
            <p class="post_result">Предварительный просмотр</p>
            <form class="clear_constructor" method="POST" action=""><input type="submit" name="clear_constructor" value="Очистить"></form>
            <form class="public_post" method="POST" action=""><input type="submit" name="public_post" value="Опубликовать"></form>
            <div class="post_result">
                {{postResult}}
            </div>
            <div class="post_screen">
                {{postScreen}}
            </div>
        </div>
        <a class="cancel_post" href="{{profilePostLink}}"></a>
    </div>
</div>