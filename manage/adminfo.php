<?php
use Make\Database\Pdosql;
use Manage\Func as Manage;

class Adminfo extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/adminfo.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','adminfoForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/adminfo.sbm.php');
        $form->run();
    }

    public function _make(){
        global $MB;

        $manage = new Manage();
        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        if($MB['adm']!='Y'){
            $func->err_back(ERR_MSG_1);
        }

        $this->set('manage',$manage);
    }

}
