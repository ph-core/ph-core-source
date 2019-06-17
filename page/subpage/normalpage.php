<?php
class Normalpage extends \controller\Make_Controller{

    public function _init(){
        $this->layout()->category_key(1);
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/subpage/normalpage.tpl.php');
        $this->layout()->foot();
    }

    public function _make(){

    }

}
