<!DOCTYPE html>
<html>
<head>
    <?php require 'html_head.php' ?>
    <link rel="stylesheet" href="/css/article.css" />
    <link rel="stylesheet" href="/css/profile.css" />
    <link rel="stylesheet" href="/css/admin.css" />
    <link rel="stylesheet" href="/css/combobox.css" />
    <script src="/js/utils.js"></script>
    <script src="/js/image_uploader.js"></script>
</head>
<body>

    <?php
    echo $this->data['menu'];
    require 'msgbox.php'
    ?>
    <div id="main">
        <div class="banner">
            <a href="/">
                <img src="/img/logo.png" />
            </a>
        </div>
        <div id="content">
            <div>
                <div>
                    <div class="read">
                        <div>
                            <?php if (empty($this->data['subaction'])) { ?>
                            <div class="sections">
                            <h1>
                                Разделы
                            </h1>
                                <div style="text-align: center;"><div class="add"><a href="/admin/sections/add/">Новый раздел</a> </div></div>
                                <div class="updown">
                                    <?php
                                      foreach ($this->data['sections'] as $section){
                                    ?>
                                   <input name="item" id="section<?php echo $section['id'] ?>" type="checkbox" />
                                    <div>
                                        <label for="section<?php echo $section['id'] ?>"> 
                                            <?php if (!empty($section['children'])) { ?>
                                            <img src="/img/down_arrow.png" />
                                            <?php } ?>
                                            <span class="section_title">
                                                <img src="<?php echo $this->app->config['path']['section']  . $section['data_folder'] . '/main_small.png'; ?>" />
                                                <?php echo $section['title'] ?>
                                            </span>
                                            <span class="info">
                                                <span class="date">
                                                    <?php echo $section['creation_date'] ?>
                                                </span>
                                                <span class="user">
                                                    <?php echo $section['login'] ?>
                                                </span>   
                                            </span>
                                            <span class="updown_action">
                                                <span class="edit">
                                                    <a href="/admin/sections/edit/?section_id=<?php echo $section['id']; ?>">Редактировать</a>
                                                </span>
                                                <span class="remove">
                                                    <a href="javascript:void(0)">Удалить</a>
                                                </span>
                                            </span>
                                        </label>
                                        <div class="updown_content">
                                            <?php if (!empty($section['children'])) { ?>
                                            <div class="updown">
                                                <?php
                                                      foreach ($section['children'] as $child){
                                                ?>
                                                <input name="item" id="section<?php echo $child['id'] ?>" type="checkbox" />
                                                <div>
                                                    <label for="section<?php echo $child['id'] ?>">
                                                        <span class="section_title">
                                                            <img src="<?php echo $this->app->config['path']['section']  . $child['data_folder'] . '/main_small.png'; ?>" />
                                                            <?php echo $child['title'] ?>
                                                        </span>
                                                        <span class="info">
                                                            <span class="date">
                                                                <?php echo $child['creation_date'] ?>
                                                            </span>
                                                            <span class="user">
                                                                <?php echo $child['login'] ?>
                                                            </span>
                                                        </span>
                                                        <span class="updown_action">
                                                            <span class="edit">
                                                                <a href="/admin/sections/edit/?section_id=<?php echo $section['id']; ?>">Редактировать</a>
                                                            </span>
                                                            <span class="remove">
                                                                <a href="javascript:void(0)">Удалить</a>
                                                            </span>
                                                        </span>
                                                    </label>
                                                    <div class="updown_content"></div>
                                                    </div>
                                            </div>
                                            <?php }
                                                  } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                          </div>
                           
                            <?php }
                                  else if ($this->data['subaction'] == 'add' || $this->data['subaction'] == 'edit') {
                            ?>
                            
                            <h1>
                                <?php echo $this->data['subaction'] == 'add' ? 'Добавить раздел' : 'Редактировать раздел'?>
                            </h1>
                            <form name="section_form" method="post">
                                <fieldset>
                                    <label for="title">Название</label>
                                    <input name="title" id="title" type="text" required="" maxlength="50" pattern="^.+$" value="<?php if (!empty($this->data['section']['title'])) echo $this->data['section']['title']; ?>" />
                                    <label for="description">Описание</label>
                                    <textarea name="description" id="description" required="" pattern="^.+$"><?php if (!empty($this->data['section']['description'])) echo $this->data['section']['description']; ?></textarea>
                                    <label>Категория</label>
                                    <div class="js-combobox" js-combobox-selected="<?php if (isset($this->data['section']['type'])) echo $this->data['section']['type']; ?>" id="cat_combo">
                                        <div js-combobox-option="0">Солнце и Солнечная система</div>
                                        <div js-combobox-option="1">Планеты</div>
                                        <div js-combobox-option="2">Спутники</div>
                                        <div js-combobox-option="3">Другое</div>
                                    </div>
                                    <div style="display: none;">
                                        <label>Планета</label>
                                        <div class="js-combobox" js-combobox-selected="<?php if (isset($this->data['section']['parent_id'])) echo $this->data['section']['parent_id']; ?>" id="planet_combo">
                                            <?php foreach ($this->data['planets'] as $planet) {
                                                      echo "<div js-combobox-option={$planet['id']}>{$planet['title']}</div>";
                                                  } ?>

                                        </div>
                                    </div>

                                    <label for="data_folder">Папка с данными</label>
                                    <input name="data_folder" id="data_folder" type="text" required="" maxlength="255" pattern="^[A-Za-z0-9_\s\/]{1,255}$" value="<?php if (!empty($this->data['section']['data_folder'])) echo $this->data['section']['data_folder']; ?>" />
                                    <label for="allow_user_articles">Разрешить пользователям предлагать публикации</label>
                                    <input name="allow_user_articles" id="allow_user_articles" type="checkbox" <?php if (!empty($this->data['section']['allow_user_articles'])) echo 'checked'; ?> />
                                    <br/>
                                    <label for="show_main">Отображать на главной</label>
                                    <input name="show_main" id="show_main" type="checkbox" <?php if (!empty($this->data['section']['show_main'])) echo 'checked'; ?>/>
                                </fieldset>

                                <fieldset class="section_images">
                                    <legend>Изображения</legend>
                                    <div>
                                        <div id="big_image">
                                            <div>
                                                <div>
                                                    <img src="<?php 
                                      $df;
                                      if (!empty($this->data['section']['data_folder']))
                                          $df = $this->app->config['path']['section'] . $this->data['section']['data_folder'];
                                      if (!empty($df) && file_exists(PATH_SECTION . $this->data['section']['data_folder'] . '/main.png')) 
                                          echo $df . '/main.png'; else echo '/img/nophoto.png';?>" />
                                                    <div class="tip">Большое изображение.<br />Оно отображается на главной странице и в нижнем правом углу при переходе к соответсвующему разделу.</div>
                                                </div>
                                            </div>

                                            <div class="buttons">
                                                <label class="upload">Загрузить</label>
                                                <label class="remove">Удалить</label>
                                                <label class="reset">Отменить</label>
                                            </div>
                                        </div>
                                        <div id="small_image">
                                            <div>
                                                <div>
                                                    <img src="<?php if (!empty($df) && file_exists(PATH_SECTION . $this->data['section']['data_folder'] . '/main_small.png')) 
                                                                  echo $df . '/main_small.png'; else echo '/img/nophoto.png';?>" />
                                                    <div class="tip">Маленькое изображение.<br />Оно отображается в меню.</div>
                                                </div>
                                            </div>

                                            <div class="buttons">
                                                <label class="upload">Загрузить</label>
                                                <label class="remove">Удалить</label>
                                                <label class="reset">Отменить</label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <input type="hidden" name="cat_id" id="cat_id" />
                                <input type="hidden" name="planet_id" id="planet_id" />
                                <input type="hidden" name="big_image_action" value="<?php echo IMAGE_NOACTION ?>" />
                                <input type="hidden" name="big_image_path" />
                                <input type="hidden" name="small_image_action" value="<?php echo IMAGE_NOACTION ?>" />
                                <input type="hidden" name="small_image_path" />
                                <fieldset>
                                    <input type="submit" name="section_submit" id="section_submit" value="ОК" />
                                    <input type="reset" />
                                </fieldset>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php include('admin_aside.php'); ?>
            </div>
            <?php include('footer.php'); ?>
        </div>
    </div>


</body>
</html>
<script src="/js/sticky.js"></script>
<script>
    <?php if (!empty($this->data['subaction']) && ($this->data['subaction'] == 'add' || $this->data['subaction'] == 'edit')) { ?>
    $('#cat_combo').change(function () {
        if ($(this).attr('js-combobox-selected') == 2)
            $(this).next().css('display', 'block');
        else
            $(this).next().css('display', 'none');

        $('#cat_id').attr('value', $(this).attr('js-combobox-selected'));
    });

    $('#planet_combo').change(function () {
        $('#planet_id').attr('value', $(this).attr('js-combobox-selected'));
    });
    <?php } ?>
</script>
<script src="/js/combobox.js"></script>
<script>
    <?php if (empty($this->data['subaction'])) { ?>
    $('input[name=item]').change(function () {
        var u = $(this).next().find('.updown_content');
        var ch = $(this).is(':checked');
        if (ch) {
            var h = u.children().height();
            u.height(h==null ? 0 : h);
        }
        else u.height(0);
    });

    $('.remove').click(function(){
        var j = $.get('/admin/sections/delete/?section_id=' + $(this).closest('label').attr('for').replace('section', ''), {}, function(){
            location.reload();
        }).fail(function(){
            messageBox('<p>Хьюстон, у нас проблемы!</p>' + j.responseText, 'left');
        });
    });

    <?php } else if ($this->data['subaction'] == 'add' || $this->data['subaction'] == 'edit') { ?>

    var lock = false;

    var bigUploader = new ImageUploader(cont, false, true, <?php echo $this->app->config['pulse']['frequency'] * 1000; ?>);
    var smallUploader = new ImageUploader(cont, false, true, <?php echo $this->app->config['pulse']['frequency'] * 1000; ?>);

    bigUploader.onStartUploading = function () {
        lock = true;
        $('#big_image .upload').addClass('loading');
    }

    bigUploader.onUploaded = function (images) {
        lock = false;
        $('#big_image .upload').removeClass('loading');
        if (images.length) {
            $('input[name=big_image_action]').attr('value', <?php echo IMAGE_ADD ?>);
            $('input[name=big_image_path]').attr('value', images[0]);
            var d = new Date();
            $('#big_image img').attr('src', images[0] + '?' + d.getTime());
        }
    }

    smallUploader.onStartUploading = function () {
        lock = true;
        $('#small_image .upload').addClass('loading');
    }

    smallUploader.onUploaded = function (images) {
        lock = false;
        $('#small_image .upload').removeClass('loading');
        if (images.length) {
            $('input[name=small_image_action]').attr('value', <?php echo IMAGE_ADD ?>);
            $('input[name=small_image_path]').attr('value', images[0]);
            var d = new Date();
            $('#small_image img').attr('src', images[0] + '?' + d.getTime());
        }
    }

    bigUploader.onError = smallUploader.onError = function (err) {
        messageBox('<p>Хьюстон, у нас проблемы!</p>' + err, 'left');
    }

    $('#big_image .upload').click(function () {
        if (lock) return;
        bigUploader.upload();
    });

    $('#big_image .remove').click(function () {
         if (lock) return;
         $('input[name=big_image_action]').attr('value', <?php echo IMAGE_DELETE ?>);
        bigUploader.delete($('#big_image img').attr('src'));
        $('#big_image img').attr('src', '/img/nophoto.png');
    });

    $('#small_image .upload').click(function () {
        if (lock) return;
        smallUploader.upload();
    });

    $('#small_image .remove').click(function () {
         if (lock) return;
         $('input[name=small_image_action]').attr('value', <?php echo IMAGE_DELETE ?>);
         smallUploader.delete($('#small_image img').attr('src'));
        $('#small_image img').attr('src', '/img/nophoto.png');
    });

    $('#big_image .reset').click(function () {
        if (lock) return;
        $('input[name=big_image_action]').attr('value', <?php echo IMAGE_NOACTION ?>);
        bigUploader.delete($('#big_image img').attr('src'));
        $('#big_image img').attr('src', '<?php if (!empty($this->data['section']['data_folder']) && file_exists(PATH_SECTION . $this->data['section']['data_folder'] . '/main.png')) echo $df . '/main.png'; else echo '/img/nophoto.png';?>');
    });

    $('#small_image .reset').click(function () {
        if (lock) return;
        $('input[name=big_image_action]').attr('value', <?php echo IMAGE_NOACTION ?>);
        bigUploader.delete($('#small_image img').attr('src'));
        $('#small_image img').attr('src', '<?php if (!empty($this->data['section']['data_folder']) && file_exists(PATH_SECTION . $this->data['section']['data_folder'] . '/main_small.png')) echo $df . '/main_small.png'; else echo '/img/nophoto.png';?>');
    });

    $(section_form).submit(function (e) {
        e.preventDefault();
        if (lock) return;
        lock = true;
            $('#section_submit').addClass('loading');
            var j = $.post('?<?php if (isset($this->data['section']['id'])) echo 'section_id=' . $this->data['section']['id'] . '&' ?>save=1', $(this).serialize(), function () {
                <?php if ($this->data['subaction'] == 'add') { ?>
                messageBox('<p>Раздел добавлен!</p><a href="' + j.responseText + '">Добавить публикацию</a>', 'left');
                <?php } else if ($this->data['subaction'] == 'edit') { ?>
                messageBox('<p>Все изменения успешно внесены!</a>', 'left');
                <?php } ?>
           }).fail(function(){
               messageBox('<p>Хьюстон, у нас проблемы!</p>' + j.responseText, 'left');
           }).always(function () {
               lock = false;
                $('#section_submit').removeClass('loading');
           });
    });
    <?php } ?>
</script>