<?php

require_once '/include/ControllerException.php';
const USER_ANY = 0;
const USER_REGISTERED = -1;
const USER_ADMIN = -2;

abstract class ControllerBase
{
	public $db;
	public $data=array();

    function __construct($db, array $data = NULL) {
        $this->db = $db;
        $this->data =$data;
        if (isset($_SESSION['user_id']))
            if ($res = $this->db->fetch('SELECT id, login, is_admin, DATE_FORMAT(reg_date, \'%e.%m.%Y %H:%i\') AS reg_date, DATE_FORMAT(last_visit, \'%e.%m.%Y %H:%i\') AS last_visit, avatar, rating, comments_cnt FROM users WHERE id=' . $_SESSION['user_id']))
            {
                $this->data['user'] = $res[0];
            }
    }

	function renderView($view) {
		include(PATH_VIEW . $view . '.php');
    }

    function validateRights(array $users = NULL, $throw = TRUE){
        if ($users === NULL) $users = [];
        foreach ($users as $user){
            if ($user == USER_ANY) return TRUE;
            if ($user == USER_REGISTERED && isset($_SESSION['user_id'])) return TRUE;
            if (!isset($_SESSION['user_id'])) continue;
            $userID = $_SESSION['user_id'];
            if ($user == USER_ADMIN){
                $res = $this->db->fetch("SELECT is_admin FROM users WHERE id=$userID");
                if (!$res){
                    if($throw) throw new ControllerException('Произошла ошибка проверки прав доступа.', 'Ошибка MySQL #' . $this->db->last_error());
                    else continue;
                }
                if (!$res[0]['is_admin']) continue;
                return TRUE;
            }
            if ($user>0 && $user == $userID) return TRUE;
        }
        if ($throw) throw new ControllerException(!isset($_SESSION['user_id']) ? 'Эта функция недоступна гостям.<br/>Зарегистрируйтесь или войдите с помощью существующей учетной записи.' : 'У вас недостаточно прав для выполнения этого действия.');
        else return FALSE;
    }

    abstract function process();
    abstract function render();

    function show() {
        $this->process();
        $this->render();
    }
}