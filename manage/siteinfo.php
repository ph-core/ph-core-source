<?php
use Corelib\Method;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class Siteinfo extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/siteinfo.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','siteinfoForm');
        $form->set('type','multipart');
        $form->set('action',PH_MANAGE_DIR.'/siteinfo.sbm.php');
        $form->run();
    }

    public function _func(){
        function set_checked($arr,$val){
            $setarr = array(
                'Y' => '',
                'N' => ''
            );
            foreach($setarr as $key => $value){
                if($key==$arr[$val]){
                    $setarr[$key] = 'checked';
                }
            }
            return $setarr;
        }

        function logo_src($arr){
            return PH_DATA_DIR.'/manage/'.$arr['st_logo'];
        }

        function favicon_src($arr){
            return PH_DATA_DIR.'/manage/'.$arr['st_favicon'];
        }

        function set_chked($arr,$field,$val){
            if($arr['st_'.$field]==$val){
                return 'checked';
            }
        }

        function mb_division($arr){
            $ex = explode('|',$arr['st_mb_division']);
            $arr = array();
            for($i=1;$i<=count($ex);$i++){
                $arr[$i] = $ex[(int)$i-1];
            }
            return $arr;
        }
    }

    public function _make(){
        $manage = new Manage();
        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        $manage->make_target('사이트 기본 정보|기본 플러그인 사용 설정|정책 및 약관|외부 메일서버 설정|여분필드');

        $sql->query(
            $sql->scheme->siteinfo('select:siteinfo'),''
        );
        $arr = $sql->fetchs();

        $ex = explode('|',$arr['st_exp']);
        for($i=1;$i<=10;$i++){
            $arr['st_'.$i.'_exp'] = $ex[$i - 1];
        }

        if($arr['st_logo']!=''){
            $is_logo_show = true;
        }else{
            $is_logo_show = false;
        }

        if($arr['st_favicon']!=''){
            $is_favicon_show = true;
        }else{
            $is_favicon_show = false;
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
        $this->set('is_logo_show',$is_logo_show);
        $this->set('is_favicon_show',$is_favicon_show);
        $this->set('print_target',$manage->print_target());
        $this->set('use_mobile',set_checked($arr,'st_use_mobile'));
        $this->set('use_emailchk',set_checked($arr,'st_use_emailchk'));
        $this->set('use_recaptcha',set_checked($arr,'st_use_recaptcha'));
        $this->set('use_smtp',set_checked($arr,'st_use_smtp'));
        $this->set('logo_src',logo_src($arr));
        $this->set('favicon_src',favicon_src($arr));
        $this->set('mb_division',mb_division($arr));
        $this->set('write',$write);
    }

}
