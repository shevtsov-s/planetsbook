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
                            <?php if (empty($this->data['subaction'])) { //список публикаций ?>
                            <h1>
                                Публикации
                            </h1>
                            <div style="text-align: center;"><div class="add"><a href="/admin/articles/add/">Новая статья</a> </div></div>
                            <div class="updown_head">
                                <div>
                                    Сортировка
                                    <div class="js-combobox" js-combobox-selected="<?php echo isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 0?>" id="cat_combo">
                                        <a js-combobox-option="0" href="?sort=0<?php if ($this->data['split']) echo '&split=1'; ?>">Дата добавления</a>
                                        <a js-combobox-option="1" href="?sort=1<?php if ($this->data['split']) echo '&split=1'; ?>">Популярность</a>
                                        <a js-combobox-option="2" href="?sort=2<?php if ($this->data['split']) echo '&split=1'; ?>">Алфавит</a>
                                        <a js-combobox-option="3" href="?sort=3<?php if ($this->data['split']) echo '&split=1'; ?>">Сначала непроверенные</a>
                                    </div>
                                </div>
                                <div>
                                    <label for="section_split">Разбить по разделам</label>
                                    <input type="checkbox" id="section_split" name="section_split" <?php if (!empty($this->data['split'])) echo 'checked' ?> />
                                </div>
                            </div>
                            <div class="updown">
                                <?php
                                  $old_section = '';
                                foreach ($this->data['articles'] as $article) {  ?>
                                <?php if (!empty($this->data['split']) && $old_section != $article['section_title']) { ?>
                                <h1>
                                    <?php echo $article['section_title'] ?>
                                </h1>
                                <?php
                                          $old_section = $article['section_title'];
                                      } ?>
                                <input name="item" id="article<?php echo $article['article_id'] ?>" type="checkbox" />
                                <div>
                                    <label for="article<?php echo $article['article_id'] ?>">
                                        <img src="/img/down_arrow.png" />
                                        <a href=<?php echo $article['href']?>>
                                            <?php echo $article['title'] ?>
                                        </a>
                                        <span class="info">
                                            <span class="date">
                                                <?php echo $article['pub_date'] ?>
                                            </span>
                                            <span class="user">
                                                <?php echo $article['login'] ?>
                                            </span>
                                            <span class="views">
                                                <?php echo $article['views'] ?>
                                            </span>
                                            <?php if (!$this->data['split']) { ?>
                                            <span class="a_section">
                                                <?php echo $article['section_title'] ?>
                                            </span>
                                            <?php } ?>
                                        </span>
                                        <span class="updown_action">
                                            <span class="edit">
                                                <a href="/admin/articles/edit/?article_id=<?php echo $article['article_id']; ?>"><?php echo (!empty($article['verifier_id']) ?  'Редактировать' : 'Проверить'); ?></a>
                                            </span>
                                            <span class="remove">
                                                <a href="javascript:void(0)">Удалить</a>
                                            </span>
                                        </span>
                                    </label>
                                    <div class="updown_content"><?php echo $article['description'];?></div>
                                </div>
                                <?php } ?>
                                <div id="pages">
                                    <a href="<?php echo $this->data['page_href'] . '&'?>page=2"><<</a><a href="<?php echo $this->data['page_href'] . '&' ?>page=<?php echo $this->data['page'] - 1; ?>"><</a><?php for ($i = $this->data['left_page']; $i<=$this->data['right_page']; $i++) echo "<a " .  ($i == $this->data['page']? "class='active'" : "") . " href='" . $this->data['page_href'] . "&page=$i'>$i</a>";  ?><a href="<?php echo $this->data['page_href'] . '&' ?>page=<?php echo $this->data['page'] + 1; ?>">></a><a href="<?php echo $this->data['page_href'] . '&' ?>page=<?php echo $this->data['count_page']; ?>">>></a>
                                </div>
                            </div>
                            <?php } else if ($this->data['subaction'] == 'add' || $this->data['subaction'] == 'edit') { //форма для добавления/редактирования ?>
                            <h1>
                                <?php if ($this->data['subaction'] == 'edit') echo 'Редактировать';
                                      else if ($this->data['user']['is_admin']) echo 'Опубликовать';
                                      else echo 'Добавить'; ?> статью
                            </h1>
                            <form name="pub_form" method="post">
                                <fieldset>
                                    <legend>Оглавление</legend>
                                    <label for="title">Название</label>
                                    <input name="title" id="title" type="text" required="" maxlength="100" pattern="^.+$" value="<?php if (!empty($this->data['article']['title'])) echo $this->data['article']['title']; ?>"/>
                                    <label for="description">Описание</label>
                                    <textarea name="description" id="description" required="" pattern="^.+$"><?php  if (!empty($this->data['article']['description'])) echo $this->data['article']['description']; ?></textarea>
                                    <label>Раздел</label>
                                    <div class="js-combobox" id="section_combo" js-combobox-selected="<?php if (!empty($this->data['article']['section_id'])) echo $this->data['article']['section_id']; else if (isset($_GET['section']) && is_numeric($_GET['section'])) echo $_GET['section']; ?>">
                                        <?php if (!empty($this->data['sections'])) {
                                                          foreach ($this->data['sections'] as $section) {
                                                              echo "<div js-combobox-option='{$section['id']}'>{$section['title']}</div>";
                                                          }
                                                      }?>
                                    </div>
                                </fieldset>
                                <fieldset id="edit_content">
                                    <legend>Содержание</legend>

                                    <div id="tools">
                                        <?php include 'bbtools.php' ?>
                                        <div>
                                            <img class='comm_preview' src='/img/eye.png' />
                                            <div class='tip'>Предпросмотр</div>
                                        </div>
                                    </div>

                                    <div id="article_content">
                                        <textarea id="contents" name="contents" required=""><?php if (!empty($this->data['article']['contents'])) echo $this->data['article']['contents']; ?></textarea>
                                    </div>
                                    <h2>Прикрепленные изображения</h2>
                                    <div class="img_thumbs">
                                        <?php if (!empty($this->data['article']['images'])) {
                                            foreach ($this->data['article']['images'] as $img) {
                                            ?>
                                        <div>
                                            <div class='thumb_action'>
                                                <div>
                                                    <div class='tip'>Вставить ВВ-код</div>
                                                </div>
                                                <div>
                                                    <div class='tip'>Удалить</div>
                                                </div>
                                            </div>
                                            <img src='<?php echo $img; ?>' /> <div><?php echo pathinfo($img, PATHINFO_BASENAME); ?></div>
                                        </div>
                                        <?php }} ?>
                                    </div>
                                </fieldset>
                                <input type="hidden" name="section_id" id="section_id" />
                                <fieldset>
                                    <input type="submit" name="pub_submit" id="pub_submit" value="ОК" />
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

    $('#section_combo').change(function () {
        $('#section_id').attr('value', $(this).attr('js-combobox-selected'));
    });

    <?php if (isset($_REQUEST['section'])) { ?>
    $('#section_combo').attr('js-combobox-selected', '<?php echo $_REQUEST['section']; ?>');
    $('#section_combo').change();
     <?php }} ?>

</script>
<script src="/js/combobox.js"></script>
<script>

    <?php if (empty($this->data['subaction'])) { ?>


    <?php if (isset($this->data['splitter_href'])) { ?>
    $('#section_split').change(function () {
        location.assign('<?php echo $this->data['splitter_href']; ?>');
    });
    <?php } ?>

    $('.remove > a').click(function () {
        var j = $.get('/admin/articles/delete/?article_id=' + $(this).closest('label').attr('for').replace('article', ''), {}, function () {
            location.reload();
        }).fail(function () {
            messageBox(j.responseText, 'left');
        });
    });
<?php } 
          
          
    
    
     else if ($this->data['subaction'] == 'add' || $this->data['subaction'] == 'edit') { ?>

    var lock = false;
    var uploader = new ImageUploader(cont, true, false, <?php echo $this->app->config['pulse']['frequency'] * 1000 ?>);

    uploader.onStartUploading = function () {
        lock = true;
    }

    uploader.onUploaded = function (images) {
        lock = false;
        $('#upload_avatar').removeClass('loading');
        for (var i = 0; i < images.length; i++) {
            var s = images[i].substr(images[i].lastIndexOf('/') + 1);
            if (window.contents)
                $(contents).first().wrapSelected('\r\n[figure]\r\n[img]' + s + '[/img]\r\n[figcaption]', '[/figcaption]\r\n[/figure]\r\n');
            $('#edit_content > .img_thumbs').append("<div><div class='thumb_action'><div><div class='tip'>Вставить ВВ-код</div></div><div><div class='tip'>Удалить</div></div></div><img src='" + images[i] + "' /> <div>" + s + "</div></div>")
        }
    }

    uploader.onError = function (err) {
        messageBox('<p>Хьюстон, у нас проблемы!</p>' + err, 'left');
    }

    uploader.onDeleted = function (img) {
        if (img) img.parent().remove();
    }

    $('#tools').click(function (e) {
        var t = $(e.target);
        if (t.hasClass('comm_bold')) $(contents).makeBold();
        else if (t.hasClass('comm_italic')) $(contents).makeItalic();
        else if (t.hasClass('comm_underline')) $(contents).makeUnderline();
        else if (t.hasClass('comm_strike')) $(contents).makeStrike();
        else if (t.hasClass('comm_sub')) $(contents).makeSub();
        else if (t.hasClass('comm_sup')) $(contents).makeSup();
        else if (t.hasClass('comm_left_align')) $(contents).makeLeft();
        else if (t.hasClass('comm_center_align')) $(contents).makeCenter();
        else if (t.hasClass('comm_right_align')) $(contents).makeRight();
        else if (t.hasClass('comm_justify_align')) $(contents).makeJustify();
        else if (t.hasClass('comm_ul')) $(contents).makeUL();
        else if (t.hasClass('comm_ol')) $(contents).makeOL();
        else if (t.hasClass('comm_url')) $(contents).makeURL();
        else if (t.hasClass('comm_img')) { if (lock) return; uploader.upload();}
        else if (t.hasClass('comm_help')) {
            var j = $.get('/comments/help/', {}, function(){
                messageBox(j.responseText, 'left', '60%');
            }).fail(function(){
                messageBox('Хьюстон, у нас проблемы!' + j.responseText, 'left');
            })
        }
    });

    var preview = false;

    $('.comm_preview').click(function () {
        lock = true;
        var transitionTimeout = 0; //время ожидания для ручного вызова события endTransition
        var d = $(article_content).transitionDuration();
        for (var i = 0; i < d.length; i++)
            if (parseFloat(d[i]) > transitionTimeout) transitionTimeout = parseFloat(d[i]);
        transitionTimeout *= 1000; transitionTimeout += 50;
        $('.comm_preview').attr('src', '/img/loading.gif');

        var callback = function (data) {
            $(article_content).transitionEnd(function () {
                lock = false;
                $('.comm_preview').attr('src', preview ? '/img/eye.png' : '/img/edit.png');
                $('.comm_preview + .tip').html(preview ? 'Предпросмотр' : 'Редактировать');
                var dt = new Date;
                if (preview) {
                    $('#article_content > div').remove();
                    $(contents).css('display', 'block');
                }
                else {
                    var d = $('<div><article>' + data + '</article></div>');
                    d.find('img').each(function () {
                        $(this).attr('src', $(this).attr('src') + '?' + dt.getTime());
                    });
                    $(contents).css('display', 'none');
                    d.insertAfter($(contents));
                }
                preview = !preview;
                setTimeout(function () { $(article_content).removeClass('invisible'); }, 0);
            }, transitionTimeout);
            $(article_content).addClass('invisible');
        }

        if (preview) {
            callback();
        }
        else {
            var ii = [0];
        $('.img_thumbs img').each(function(){
            ii.push($(this).attr('src'));    
        });
        var j = $.post('/admin/preview/', { text: $(contents).val(), images: ii }, callback).fail(function () { messageBox(j.responseText, 'center'); }).always(function(){
            $('.comm_preview').attr('src', preview ? '/img/eye.png' : '/img/edit.png');
            lock = false;
        });
        }
    });

    $(pub_form).submit(function (e) {
        if (lock) return;
        lock = true;
        e.preventDefault();
        $('#pub_submit').addClass('loading');
        var j = $.post('?<?php if ($this->data['subaction'] == 'edit') echo 'article_id=' . $this->data['article']['id'] . '&'; ?>save=1', $(this).serialize(), function () {
            messageBox('<?php if ($this->data['subaction'] == 'add') {
                                  if ($this->data['user']['is_admin'])
                                      echo '<p>Спасибо за публикацию!</p><p>Ваша статья теперь доступна для просмотра <a href="\' + j.responseText + \'">здесь</a></p>';
                                  else
                                      echo  '<p>Большое спасибо за предложенную статью!</p>В ближайшее время мы проверим и опубликуем ее.</p>';
                              }
                            else if ($this->data['subaction'] == 'edit'){
                                 if (!empty($this->data['article']['verifier_id']))
                                     echo  '<p>Все изменения успешно внесены.</p>';
                                 else
                                     echo  '<p>Спасибо! Теперь эта статья доступна для просмотра всем пользователям.</p>';
                              }
                                  ?>', 'left');
        }).fail(function () {
            messageBox(j.responseText, 'left');
        }).always(function () {
            lock = false;
            $('#pub_submit').removeClass('loading');
        });
    });

    $(pub_form).on('reset', function () {
        $('.thumb_action > div:nth-child(2)').click();
    });

    $('.img_thumbs').click(function (e) {
        var img = $(e.target).parent().next();
        if ($(e.target).is('.thumb_action > div:nth-child(2)')) {
<?php if (isset($this->data['subaction']) && $this->data['subaction'] == 'edit') { ?>
            var j = $.post('/admin/articles/deleteimg/?article_id=<?php echo $this->data['article']['id']; ?>&image_path=' + img.attr('src')).always(function () {
<?php } ?>
                uploader.delete(img.attr('src'), img);
          <?php if (isset($this->data['subaction']) && $this->data['subaction'] == 'edit') { ?>});<?php } ?>

        }
        else if ($(e.target).is('.thumb_action > div:nth-child(1)')) {
            if (window.contents)
                $(contents).first().wrapSelected('\r\n[figure]\r\n[img]' + img.attr('src') + '[/img]\r\n[figcaption]', '[/figcaption]\r\n[/figure]\r\n');
        }
    });

    <?php } ?>

</script>