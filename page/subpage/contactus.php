<?php
class Contactus extends \controller\Make_Controller{

    public function _init(){
        $this->layout()->category_key(6);
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/subpage/contactus.tpl.php');
        $this->layout()->foot();
    }

    public function module(){
        $module = new \Module\Contactform\Make_Controller();
        $module->run();
    }

    public function _make(){

    }

}
