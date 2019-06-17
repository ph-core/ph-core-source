<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class Modifytpl extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/modifytpl.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','modifytplForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/modifytpl.sbm.php');
        $form->run();
    }

    public function _make(){
        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Manage\\Scheme');

        $req = Method::request('GET','idx');

        Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');

        $sql->query(
            $sql->scheme->mailtpl('select:mailtpl'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Func::err_back('템플릿이 존재하지 않습니다.');
        }

        $arr = $sql->fetchs();
        $sql->specialchars = 0;
        $sql->nl2br = 0;
        $arr['html'] = $sql->fetch('html');

        $write = array();
        if(isset($arr)){
            foreach($arr as $key => $value){
                $write[$key] = $value;
            }
        }else{
            $write = null;
        }

        $this->set('manage',$manage);
        $this->set('write',$write);
    }

}
