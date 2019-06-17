<?php
class Sitemap extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/sitemap.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','sitemapListForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/sitemapList.sbm.php');
        $form->run();
    }

    public function form2(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','sitemapMofidyForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/sitemapModify.sbm.php');
        $form->run();
    }

    public function _make(){

    }

}
