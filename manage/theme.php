<?php
use Manage\Func as Manage;

class Theme extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/theme.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','themeForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/theme.sbm.php');
        $form->run();
    }

    public function _func(){
        function theme_total(){
            global $THEME;
            return count($THEME);
        }

        function thumbnail($arr){
            return PH_DIR.'/theme/'.$arr['theme'].'/theme.thumb.jpg';
        }
    }

    public function _make(){
        global $THEME;

        $manage = new Manage();

        $print_arr = array();

        foreach($THEME as $key => $value){

            $xml_file = PH_PATH.'/theme/'.$value.'/theme.info.xml';
            if(file_exists($xml_file)){
                $load_xml = simplexml_load_file($xml_file);
            }

            $xml_arr = array();
            $xml_arr['theme'] = $value;
            $xml_arr['name'] = $load_xml[0]->name;
            $xml_arr['developer'] = $load_xml[0]->developer;
            $xml_arr['version'] = $load_xml[0]->version;
            $xml_arr['develDate'] = $load_xml[0]->develDate;
            $xml_arr['updateDate'] = $load_xml[0]->updateDate;
            $xml_arr['website'] = $load_xml[0]->website;
            $xml_arr[0]['thumbnail'] = thumbnail($xml_arr);

            $print_arr[] = $xml_arr;

            $this->set('manage',$manage);
            $this->set('theme_total',theme_total());
            $this->set('print_arr',$print_arr);
        }
    }
}
