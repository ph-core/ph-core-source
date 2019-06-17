<?php
use Manage\Func as Manage;

class Addmb extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/addmb.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','addmbForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/addmb.sbm.php');
        $form->run();
    }

    public function _make(){
        $manage = new Manage();

        $this->set('manage',$manage);
    }

}
