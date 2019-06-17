<?php
use Corelib\Method;
use Corelib\Func;
use Manage\Func as Manage;

class Make extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_make();
        $this->load_tpl(MOD_CONTENTS_PATH.'/manage.set/html/make.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','makeContentsForm');
        $form->set('type','html');
        $form->set('action',MOD_CONTENTS_DIR.'/manage.set/make.sbm.php');
        $form->run();
    }

    public function _make(){
        $manage = new Manage();

        Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');

        $this->set('manage',$manage);
    }

}
