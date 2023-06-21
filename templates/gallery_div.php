<div class="gallery_div">
    <div class="gallery_div_container">
        <a class="previous_photo" href="{{getPreviousPhoto}}"></a>
        <div class="view_photo">
            <a class="current_photo" href="{{getNextPhoto}}"><img class="view_photo" alt="Пользователь {{getGalleryLogin}}. Просмотр фотографий" src="{{getCurrentPhoto}}"></a>
        </div>
        <a class="next_photo" href="{{getNextPhoto}}"></a>
        <div class="clear"></div>
        <a class="cancel" href="{{profileGalleryLink}}"></a>
    </div>
    <div class="gallery_comments">
        {{deleteCommentGalleryForm}}
        {{commentGallery}}
        <p><b>Комментарии:</b></p><br>
        {{commentsGallery}}
    </div>
    <div style="height: 10px;"></div>
</div>