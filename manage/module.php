<?php
use Manage\Func as Manage;

class Module extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/module.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _func(){
        function module_total(){
            global $MODULE;
            return count($MODULE);
        }
    }

    public function _make(){
        global $MODULE;

        $manage = new Manage();

        $print_arr = array();

        foreach($MODULE as $key => $value){

            $xml_file = PH_PATH.'/mod/'.$value.'/manage.set/module.info.xml';
            if(file_exists($xml_file)){
                $load_xml = simplexml_load_file($xml_file);
            }

            $xml_arr = array();
            $xml_arr['module'] = $value;
            $xml_arr['name'] = $load_xml[0]->name;
            $xml_arr['developer'] = $load_xml[0]->developer;
            $xml_arr['version'] = $load_xml[0]->version;
            $xml_arr['develDate'] = $load_xml[0]->develDate;
            $xml_arr['updateDate'] = $load_xml[0]->updateDate;
            $xml_arr['website'] = $load_xml[0]->website;

            $json_file = PH_MOD_PATH.'/'.$value.'/manage.set/navigator.json';
            if(file_exists($json_file)){
                $load_json = json_decode(file_get_contents($json_file),true);
                $xml_arr['golink'] = $load_json[0]['href'];
            }

            $print_arr[] = $xml_arr;

            $this->set('module_total',module_total());
            $this->set('print_arr',$print_arr);
            $this->set('manage',$manage);

        }
    }

}
