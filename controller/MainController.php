<?php 
require_once 'ControllerBase.php';
require_once 'MenuController.php';

class MainController extends MenuController
{ 

	function process($action){
        //��������� ����
        parent::process($action);

        //������������ ������ ��� ��������

        $show_res = [];
        foreach ($this->data['menu'] as &$val)
        {
            if ($val['show_main'])
            {
                if ($val['type'] != 2){
                    $show_res[$val['id']]['title'] = $val['title'];
                    $show_res[$val['id']]['description'] = $val['description'];
                    $show_res[$val['id']]['image'] = $val['big_file'];
                    $show_res[$val['id']]['href'] = $val['href'];
                    if (!isset($show_res[$val['id']]['moons'])) $show_res[$val['id']]['moons'] = [];
                }
                else
                    $show_res[$val['parent_id']]['moons'][] = [ 
                        'title'         =>  $val['title'], 
                        'description'   =>  $val['description'], 
                        'image'         =>  $val['big_file'], 
                        'href'          =>  $val['href']
                    ];
            }
        }
        unset($val);

        $this->data['show'] = $show_res; 
	}

    function render(){
        $this->data['menu'] = parent::render();
		$this->renderView('main');
    }
}
?>