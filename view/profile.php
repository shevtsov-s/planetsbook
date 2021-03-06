<!DOCTYPE html>
<html>
<head>
<?php require 'html_head.php' ?>
    <link rel="stylesheet" href="/css/article.css" />
    <link rel="stylesheet" href="/css/profile.css" />
    <link rel="stylesheet" href="/css/combobox.css" />
    <link rel="stylesheet" href="/css/storage.css" />
    <link rel="stylesheet" href="/css/fullscreen.css" />
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
                            <div id="edit_log" class="log_message"><div></div></div>
                            <div>
                                <?php if ($this->outputMode == OUT_SHOW_PROFILE) { ?>
                                <section>
                                    <img src="<?php echo empty($this->data['profile']['avatar']) ? '/img/noavatar.png' : $this->data['profile']['avatar']?>" />
                                </section>
                                <section class="user_info">
                                    <section>
                                        <h1><?php echo $this->data['profile']['login']?></h1>
                                        <h2><?php echo ($this->data['profile']['is_admin'] ? 'Администратор' : 'Пользователь')?></h2>
                                    </section>
                                    <section class="general_info">
                                        <div>Зарегистрирован <?php echo $this->data['profile']['reg_date']?></div>
                                        <div>В последний раз был на сайте <?php echo (empty($this->data['profile']['last_visit']) ? 'неизвестно когда' : $this->data['profile']['last_visit'])?></div>
                                        <div>Рейтинг: <?php
                                            $r = $this->data['profile']['rating'];
                                            if ($r > 0) echo '<span style="color:green">+' . $this->data['profile']['rating'] . '</span>';
                                            else if ($r == 0) echo '<span style="color:white">' . $this->data['profile']['rating'] . '</span>';
                                            else echo '<span style="color:red">' . $this->data['profile']['rating'] . '</span>';?>
                                            </div>
                                        <div>Комментариев: <?php echo $this->data['profile']['comments_cnt']?></div>
                                    </section>
                                    <section class="contacts">
                                        <h2>Контакты</h2>
                                        <?php if (!empty($this->data['profile']['email'])) { ?>
                                        <div>E-mail: <a href="mailto:<?php echo $this->data['profile']['email']?>"><?php echo $this->data['profile']['email']?></a></div>
                                        <?php } ?>
                                        <?php if (!empty($this->data['profile']['real_name'])) { ?>
                                        <div>Настоящее имя: <?php echo $this->data['profile']['real_name']?></div>
                                        <?php } ?>
                                        <?php if (!empty($this->data['profile']['skype'])) { ?>
                                        <div>Skype: <?php echo $this->data['profile']['skype']?></div>
                                        <?php } ?>
                                        <?php if (!empty($this->data['profile']['vk'])) { ?>
                                        <div>ВКонтакте: <a href="<?php echo 'http://vk.com/' . $this->data['profile']['vk']?>"><?php echo $this->data['profile']['vk']?></a></div>
                                        <?php } ?>
                                        <?php if (!empty($this->data['profile']['facebook'])) { ?>
                                        <div>Facebook: <a href="<?php echo $this->data['profile']['facebook']?>"><?php echo $this->data['profile']['facebook']?></a></div>
                                        <?php } ?>
                                        <?php if (!empty($this->data['profile']['twitter'])) { ?>
                                        <div>Twitter: <a href="<?php $this->data['profile']['twitter']?>"><?php echo $this->data['profile']['twitter']?></a></div>
                                        <?php } ?>
                                        <?php if (!empty($this->data['profile']['site'])) { ?>
                                        <div>Сайт: <a href="<?php echo $this->data['profile']['site']?>"><?php echo $this->data['profile']['site']?></a></div>
                                        <?php } ?>
                                        <?php if (!empty($this->data['profile']['from_where'])) { ?>
                                    <div>
                                        Откуда: <?php echo htmlspecialchars($this->data['profile']['from_where'], ENT_QUOTES) ?></div>
                                        <?php } ?>
                                    </section>
                                </section>
                                <?php } else if ($this->outputMode == OUT_EDIT_PROFILE) {?>
                                <h1><?php echo $this->data['profile']['login']?></h1>
                                <form name="edit_profile" method="post">
                                    <fieldset class="edit_avatar">
                                        <legend>Аватар</legend>
                                        <div>
                                            <div>
                                                <img id="avatar_img" src="<?php  echo empty($this->data['profile']['avatar']) ? '/img/noavatar.png' : $this->data['profile']['avatar'] ?>" />
                                            </div>
                                            <div>

                                                <label id="upload_avatar">Выбрать</label>
                                                <label id="remove_avatar">Удалить</label>
                                                <label id="reset_avatar">Отменить</label>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Смена пароля</legend>
                                        <div>
                                            <label for="old_psw">Старый пароль</label>
                                        <input type="password" name="old_psw" pattern="^[A-Za-z0-9_\<\>\!\~\@\#\$\%\^\&\*\(\)\+\=\-_\?\:\;\,\.\/\\]{6,}$" />
                                        </div>
                                        <div>
                                            <label for="new_psw">Новый пароль</label>
                                        <input type="password" name="new_psw" pattern="^[A-Za-z0-9_\<\>\!\~\@\#\$\%\^\&\*\(\)\+\=\-_\?\:\;\,\.\/\\]{6,}$" />
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Контакты</legend>
                                        <div>
                                            <label for="email">E-mail</label>
                                            <input type="email" name="email" value="<?php if (!empty($this->data['profile']['email'])) echo $this->data['profile']['email']?>"/>
                                        </div>
                                        <div>
                                            <label for="real_name">Настоящее имя</label>
                                            <input type="text" name="real_name" pattern="^[A-Za-zА-ЯЁІЇЄа-яёіїє\s]+$" maxlength="50" value="<?php if (!empty($this->data['profile']['real_name'])) echo $this->data['profile']['real_name']?>"/>
                                        </div>
                                        <div>
                                            <label for="skype">Skype</label>
                                            <input type="text" name="skype" maxlength="50" pattern="^[A-Za-z0-9_]{1,50}$" value="<?php if (!empty($this->data['profile']['skype'])) echo $this->data['profile']['skype']?>"/>
                                        </div>
                                        <div>
                                            <label for="vk">ВКонтакте</label>
                                            <input type="text" name="vk" maxlength="100" pattern="^[A-Za-z0-9_]{1,100}$" value="<?php if (!empty($this->data['profile']['vk'])) echo $this->data['profile']['vk']?>"/>
                                        </div>
                                        <div>
                                            <label for="facebook">Facebook</label>
                                            <input type="text" name="facebook" maxlength="100" pattern="^[A-Za-z0-9_]{1,100}$" value="<?php if (!empty($this->data['profile']['facebook'])) echo $this->data['profile']['facebook']?>"/>
                                        </div>
                                        <div>
                                            <label for="twitter">Twitter</label>
                                            <input type="text" name="twitter" maxlength="100" pattern="^[A-Za-z0-9_]{1,100}$" value="<?php if (!empty($this->data['profile']['twitter'])) echo $this->data['profile']['twitter']?>"/>
                                        </div>
                                        <div>
                                            <label for="site">Сайт</label>
                                            <input type="url" name="site" maxlength="100" value="<?php if (!empty($this->data['profile']['site'])) echo $this->data['profile']['site']?>"/>
                                        </div>
                                        <div>
                                            <label for="from_where">Откуда</label>
                                        <input type="text" name="from_where" maxlength="150" pattern="^.{1,150}$" value="<?php if (!empty($this->data['profile']['from_where'])) echo  htmlspecialchars($this->data['profile']['from_where'], ENT_QUOTES)?>"/>
                                        </div>
                                    </fieldset>
                                    <input type="hidden" name="avatar_action" value="0" />
                                    <input type="hidden" name="avatar_path" />
                                    <fieldset>
                                        <input type="submit" id="edit_submit" value="Отправить" />
                                        <input type="reset" />
                                    </fieldset>
                                </form>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                    <aside>
                        <div class="sticky">
                            <div>
                                <div class="section <?php if ($this->outputMode == OUT_SHOW_PROFILE) echo 'selected' ?>"><div><a href="/users/profile/?id=<?php echo $this->data['profile']['id'] ?>">Просмотр</a></div></div>
                                <?php if (isset($this->data['user']['id']) && $this->data['user']['id'] == $this->data['profile']['id']) { ?><div class="section <?php if ($this->outputMode == OUT_EDIT_PROFILE) echo 'selected' ?>"><div><a href="/users/edit/?id=<?php echo $this->data['profile']['id'] ?>">Изменить</a></div></div> <?php } ?>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
            <?php include('footer.php'); ?>
        </div>
</body>
</html>
<script>
    var sticky = $('.sticky');
    var cont = $('#content');

    $(window).scroll(function () {
        if (parseInt(cont.offset().top) < parseInt($(this).scrollTop())) sticky.addClass('sticked');
        else sticky.removeClass('sticked');
    });

    $(window).scroll();

    $(window).resize(function () { sticky.width(sticky.parent().width()) });
    $(window).resize();

    <?php if ($this->outputMode == OUT_EDIT_PROFILE) { ?>

        var lock = false;

        $(edit_profile).submit(function (e) {
            e.preventDefault();
            if (lock) return;
            lock = true;
            $('#edit_submit').addClass('loading');
            var j = $.post('/users/edit/?update=1&id=<?php echo $this->data['profile']['id'] ?>', $(this).serialize(), function(){
                $('#edit_log > div').html('<p>Все изменения были успешно внесены.</p>').parent().removeClass('fail').addClass('success').css('height', $('#edit_log > *').outerHeight(true));
                 setTimeout(function () { $('#edit_log').css('height', 0); }, 3000);
            }, "json").fail(function(){
                 $('#edit_log > div').html('<p>Хьюстон, у нас проблемы!</p>' + formatError(j.responseJSON, "message", "details")).parent().removeClass('success').addClass('fail').css('height', $('#edit_log > *').outerHeight(true));
                 setTimeout(function () { $('#edit_log').css('height', 0); }, 5000);
            }).always(function(){
                lock = false;
                $('#edit_submit').removeClass('loading');
                location.assign('#edit_log');
            });
        });

    var avatar = $('#avatar_img');

    $('#reset_avatar').click(function(){
        if (lock) return;
        $('input[name=avatar_action]').attr('value', 0);
        avatar.attr('src', '<?php echo empty($this->data['profile']['avatar']) ? '/img/noavatar.png' : $this->data['profile']['avatar'] ?>');
    });

    $('#remove_avatar').click(function () {
        if (lock) return;
        $('input[name=avatar_action]').attr('value', 2);
        avatar.attr('src', '/img/noavatar.png');

    });

    $('#upload_avatar').click(function () {
        if (lock) return;
        var s = $("<div class='js-storage' data-target='/avatar/' data-user-id='<?php echo $this->data['user']['id'] ?>' data-admin='<?php echo (int)$this->data['user']['is_admin'] ?>'></div>");
            var storage = new Storage(s);
            storage.onSelected = function(sel){
                msgboxClose();
                if (sel.length() > 0){
                    $('input[name=avatar_action]').attr('value', 1);
                    $('input[name=avatar_path]').attr('value', sel.keyAt(0));
                    avatar.attr('src', sel.valueAt(0));
                }
            }
            messageBox(s, 'left', '60%');
    });

    <?php } ?>
</script>
<script src="/js/combobox.js"></script>
<script src="/js/storage.js"></script>
<script src="/js/fullscreen.js"></script>


