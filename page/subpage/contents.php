<?php
class Contents extends \controller\Make_Controller{

    public function _init(){
        $this->layout()->category_key(2);
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/subpage/contents.tpl.php');
        $this->layout()->foot();
    }

    public function module(){
        $module = new \Module\Contents\Make_Controller();
        $module->set('key','sample');
        $module->run();
    }

    public function _make(){

    }

}
