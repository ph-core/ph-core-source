<?php
class News extends \controller\Make_Controller{

    public function _init(){
        $this->layout()->category_key(4);
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/subpage/news.tpl.php');
        $this->layout()->foot();
    }

    public function module(){
        $module = new \Module\Board\Make_Controller();
        $module->set('id','news');
        $module->run();
    }

    public function _make(){

    }

}
