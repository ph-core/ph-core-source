<?php
class Freeboard extends \controller\Make_Controller{

    public function _init(){
        $this->layout()->category_key(5);
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/subpage/freeboard.tpl.php');
        $this->layout()->foot();
    }

    public function module(){
        $module = new \Module\Board\Make_Controller();
        $module->set('id','freeboard');
        $module->run();
    }

    public function _make(){

    }

}
