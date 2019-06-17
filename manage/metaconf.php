<?php
use Make\Database\Pdosql;
use Manage\Func as Manage;

class Metaconf extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/metaconf.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','metaconfForm');
        $form->set('type','multipart');
        $form->set('action',PH_MANAGE_DIR.'/metaconf.sbm.php');
        $form->run();
    }

    public function _func(){
        function og_image_src($arr){
            return PH_DATA_DIR.'/manage/'.$arr['st_og_image'];
        }
    }

    public function _make(){
        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->siteinfo('select:siteinfo'),''
        );
        $arr = $sql->fetchs();
        $sql->specialchars = 0;
        $sql->nl2br = 0;
        $arr['st_script'] = $sql->fetch('st_script');
        $arr['st_meta'] = $sql->fetch('st_meta');

        if($arr['st_og_image']!=''){
            $is_og_image_show = true;
        }else{
            $is_og_image_show = false;
        }

        $write = array();
        if(isset($arr)){
            foreach($arr as $key => $value){
                $write[$key] = $value;
            }
        }else{
            $write = null;
        }

        $this->set('manage',$manage);
        $this->set('is_og_image_show',$is_og_image_show);
        $this->set('og_image_src',og_image_src($arr));
        $this->set('write',$write);
    }

}
