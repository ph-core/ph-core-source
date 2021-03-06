<?php
use Corelib\Func;
use Manage\Func as Manage;

class Makebn extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/makebn.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','makebnForm');
        $form->set('type','multipart');
        $form->set('action',PH_MANAGE_DIR.'/makebn.sbm.php');
        $form->run();
    }

    public function _make(){
        $manage = new Manage();

        Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');

        $this->set('manage',$manage);
    }

}
