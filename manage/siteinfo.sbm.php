<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Library\Uploader;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        $sql = new Pdosql();
        $manage = new Manage();
        $uploader = new Uploader();

        $sql->scheme('Manage\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','title,domain,description,use_mobile,use_emailchk,email,tel,mb_division,theme,use_smtp,smtp_server,smtp_port,smtp_id,smtp_pwd,script,privacy,policy,favicon_del,uploaded_favicon,logo_del,uploaded_logo,st_1,st_2,st_3,st_4,st_5,st_6,st_7,st_8,st_9,st_10,st_exp');
        $file = Method::request('FILE','favicon,logo');
        $manage->req_hidden_inp('POST');

        Valid::notnull('title',$req['title'],'');
        Valid::notnull('domain',$req['domain'],'');
        Valid::notnull('email',$req['email'],'');

        for($i=0;$i<count($req['mb_division']);$i++){
            if(!$req['mb_division'][$i]){
                Valid::error('','회원 등급별 명칭을 모두 입력해 주세요.');
            }
        }

        if($req['use_smtp']=='Y'){
            Valid::notnull('smtp_server',$req['smtp_server'],'');
            Valid::notnull('smtp_port',$req['smtp_port'],'');
            Valid::notnull('smtp_id',$req['smtp_id'],'');
            Valid::notnull('smtp_pwd',$req['smtp_pwd'],'');
        }

        $sql->query(
            $sql->scheme->siteinfo('select:siteinfo'),''
        );
        $arr = $sql->fetchs();

        $uploader->path= PH_DATA_PATH.'/manage';
        $uploader->chkpath();

        $favicon_name = '';
        if($file['favicon']['size']>0){
            $uploader->file = $file['favicon'];
            $uploader->intdict = 'ico';
            if($uploader->chkfile('match')!==true){
                Valid::error('favicon','허용되지 않는 파비콘 유형입니다.');
            }
            $favicon_name = $uploader->replace_filename($file['favicon']['name']);
            if(!$uploader->upload($favicon_name)){
                Valid::error('favicon','파비콘 업로드 실패');
            }
        }
        if(($file['favicon']['size']>0 && $arr['st_favicon']!='') || $req['favicon_del']=='checked'){
            $uploader->drop($arr['st_favicon']);
        }
        if($arr['st_favicon']!='' && !$file['favicon']['name'] && $req['favicon_del']!='checked'){
            $favicon_name = $arr['st_favicon'];
        }

        $logo_name = '';
        if($file['logo']['size']>0){
            $uploader->file = $file['logo'];
            $uploader->intdict = SET_IMGTYPE;
            if($uploader->chkfile('match')!==true){
                Valid::error('logo','허용되지 않는 로고 유형입니다.');
            }
            $logo_name = $uploader->replace_filename($file['logo']['name']);
            if(!$uploader->upload($logo_name)){
                Valid::error('logo','로고 업로드 실패');
            }
        }
        if(($file['logo']['size']>0 && $arr['st_logo']!='') || $req['logo_del']=='checked'){
            $uploader->drop($arr['st_logo']);
        }
        if($arr['st_logo']!='' && !$file['logo']['name'] && $req['logo_del']!='checked'){
            $logo_name = $arr['st_logo'];
        }

        $mb_division = implode('|',$req['mb_division']);
        $st_exp = $sql->etcfd_exp(implode('|',$req['st_exp']));

        $sql->query(
            $sql->scheme->siteinfo('update:siteinfo'),
            array(
                $req['title'],$req['domain'],$req['description'],$req['use_mobile'],$req['use_emailchk'],$req['email'],$req['tel'],$favicon_name,$logo_name,$mb_division,$req['use_smtp'],$req['smtp_server'],$req['smtp_port'],$req['smtp_id'],$req['smtp_pwd'],$req['privacy'],$req['policy'],$req['st_1'],$req['st_2'],$req['st_3'],$req['st_4'],$req['st_5'],$req['st_6'],$req['st_7'],$req['st_8'],$req['st_9'],$req['st_10'],$st_exp
            )
        );

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 변경 되었습니다.'
            )
        );
        Valid::success();
    }

}
