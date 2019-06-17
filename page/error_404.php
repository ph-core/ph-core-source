<?php
class Error_404 extends \Controller\Make_Controller{

    public function _init(){
        $this->common()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/error_404.tpl.php');
        $this->common()->foot();
    }

    public function _make(){
        global $CONF;

        $this->set('go_site',$CONF['domain']);
    }

}
