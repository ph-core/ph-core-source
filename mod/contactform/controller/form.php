<?php
namespace Module\Contactform;

class Form extends \Controller\Make_Controller{

    public function _init(){
        $this->_make();
        $this->load_tpl(MOD_CONTACTFORM_THEME_PATH.'/form.tpl.php');
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','contactForm');
        $form->set('type','html');
        $form->set('action',MOD_CONTACTFORM_DIR.'/controller/form.sbm.php');
        $form->run();
    }

    public function _make(){

    }

}
