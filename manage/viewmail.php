<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class Viewmail extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/viewmail.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _func(){
        function print_level($arr){
            global $MB;
            if($arr['to_mb']){
                return '';
            }else{
                return $arr['level_from'].' ('.$MB['type'][$arr['level_from']].') ~ '.$arr['level_to'].' ('.$MB['type'][$arr['level_to']].')';
            }
        }
    }

    public function _make(){
        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Manage\\Scheme');

        $req = Method::request('GET','idx');

        $sql->query(
            $sql->scheme->sendmail('select:sentmail'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Func::err_back('메일 발송 내역이 존재하지 않습니다.');
        }

        $arr = $sql->fetchs();
        $sql->specialchars = 0;
        $sql->nl2br = 0;
        $arr['html'] = $sql->fetch('html');
        $arr['regdate'] = Func::datetime($arr['regdate']);

        $is_level_show = false;
        $is_to_mb_show = false;

        if($arr['to_mb']){
            $is_to_mb_show = true;
        }else{
            $is_level_show = true;
        }

        $view = array();
        if(isset($arr)){
            foreach($arr as $key => $value){
                $view[$key] = $value;
            }
        }else{
            $view = null;
        }

        $this->set('manage',$manage);
        $this->set('view',$view);
        $this->set('is_level_show',$is_level_show);
        $this->set('is_to_mb_show',$is_to_mb_show);
        $this->set('print_level',print_level($arr));
    }

}
