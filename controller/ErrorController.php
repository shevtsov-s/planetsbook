<?php

require_once 'MenuController.php';

class ErrorController extends MenuController
{
    private $exception;

    function __construct($app, $db, array $data = NULL){
        parent::__construct($app, $db, $data);
        $this->useTransactions = FALSE;
        $this->exception = isset($data['exception']) &&
                           is_subclass_of($data['exception'], 'Exception') ?
                                $data['exception'] :
                                new Exception('Неизвестная ошибка');
        $this->showErrorPage = isset($data['controller']) ? $data['controller']->showErrorPage : TRUE;
    }

    function process($action){

        if ($this->db) parent::process($action);
        if ($this->showErrorPage){
            $this->data['error'] = '<p>Нам очень жаль, но что то пошло не так!</p>';
            switch (get_class($this->exception)){
                case 'ControllerException':
                    $this->data['error'] .= '<p>' . $this->exception . '</p>';
                    if ($d = trim($this->exception->getDetails())) $this->data['error'] .= '<div class="details">' . $d . '</div>';
                    http_response_code(500);
                    break;
                case 'DatabaseException':
                    $this->data['error'] .= '<p>' . $this->exception . '</p>';
                    http_response_code(500);
                    break;

                case 'HttpException':
                    switch ($this->exception->getCode()){
                        case 403:
                            $this->data['error'] = '<h1>403 <span color="#D4CECE">Forbidden</span></h1>Доступ запрещен.';
                            break;
                        case 404:
                            $this->data['error'] = '<h1>404 <span color="#D4CECE">Not Found</span></h1>Страница, которую вы запросили, не существует.';
                            break;
                        case 500:
                            $this->data['error'] = '<h1>500 <span color="#D4CECE">Internal Server Error</span></h1>Сервер не может выполнить ваш запрос из-за критической ошибки.';
                            break;
                        default:
                            $this->data['error'] = '<h1>' . $this->exception->getCode() . ' <span color="#D4CECE"></h1>Неизвестная Http-ошибка.';
                            break;
                    }
                    http_response_code($this->exception->getCode());
                    break;

                case 'ErrorException':
                    $this->data['error'] .= '<p>File: ' . $this->exception->getFile() . '<br/>Line: ' . $this->exception->getLine() . '<br/>Message: ' . $this->exception->getMessage() . '</p>';
                    http_response_code(500);
                    break;

                default:
                    $this->data['error'] .= '<p>Произошла неизвестная ошибка</p>';
                    http_response_code(500);
                    break;
            }
        }
        else{
            if ($this->exception instanceof Exception){
                $this->data['error']['code'] = $this->exception->getCode();
                $this->data['error']['message'] = $this->exception->getMessage();
                $this->data['error']['line'] = $this->exception->getLine();
                $this->data['error']['file'] = $this->exception->getFile();
                if (($this->exception instanceof ControllerException) )
                    $this->data['error']['details'] = (is_array($this->exception->getDetails())) ? array_values($this->exception->getDetails()) : $this->exception->getDetails();

                if ($this->exception instanceof HttpException)
                    http_response_code($this->exception->getCode());
                else
                    http_response_code(500);
            }
        }

    }

    function render(){
        if ($this->showErrorPage){
            $this->data['menu'] = $this->db ? parent::render() : '';
            $this->renderView('error');
        }
        else{
            echo json_encode(['error' => $this->data['error']]);
        }
    }
}
