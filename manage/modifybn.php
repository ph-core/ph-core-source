<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class Modifybn extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/modifybn.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','modifybnForm');
        $form->set('type','multipart');
        $form->set('action',PH_MANAGE_DIR.'/modifybn.sbm.php');
        $form->run();
    }

    public function _func(){
        function pc_img_src($arr){
            return PH_DATA_DIR.'/manage/'.$arr['pc_img'];
        }

        function mo_img_src($arr){
            return PH_DATA_DIR.'/manage/'.$arr['mo_img'];
        }
    }

    public function _make(){
        $req = Method::request('GET','idx');

        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->banner('select:banner'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Func::err_back('배너가 존재하지 않습니다.');
        }

        $arr = $sql->fetchs();

        $arr['hit'] = Func::number($arr['hit']);

        if($arr['pc_img']!=''){
            $is_pc_img_show = true;
        }else{
            $is_pc_img_show = false;
        }
        if($arr['mo_img']!=''){
            $is_mo_img_show = true;
        }else{
            $is_mo_img_show = false;
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
        $this->set('write',$write);
        $this->set('is_pc_img_show',$is_pc_img_show);
        $this->set('is_mo_img_show',$is_mo_img_show);
        $this->set('pc_img_src',pc_img_src($arr));
        $this->set('mo_img_src',mo_img_src($arr));
    }

}
