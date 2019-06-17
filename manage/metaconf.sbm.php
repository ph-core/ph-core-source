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
        $req = Method::request('POST','og_type,og_title,og_description,og_url,naver_verific,google_verific,script,meta,og_image_del');
        $file = Method::request('FILE','og_image');
        $manage->req_hidden_inp('POST');

        $sql->query(
            $sql->scheme->siteinfo('select:siteinfo'),''
        );
        $arr = $sql->fetchs();

        $uploader->path= PH_DATA_PATH.'/manage';
        $uploader->chkpath();

        $og_image_name = '';
        if($file['og_image']['size']>0){
            $uploader->file = $file['og_image'];
            $uploader->intdict = SET_IMGTYPE;
            if($uploader->chkfile('match')!==true){
                Valid::error('og_image','허용되지 않는 이미지 유형입니다.');
            }
            $og_image_name = $uploader->replace_filename($file['og_image']['name']);
            if(!$uploader->upload($og_image_name)){
                Valid::error('og_image','이미지 업로드 실패');
            }
        }
        if(($file['og_image']['size']>0 && $arr['st_og_image']!='') || $req['og_image_del']=='checked'){
            $uploader->drop($arr['st_og_image']);
        }
        if($arr['st_og_image']!='' && !$file['og_image']['name'] && $req['og_image_del']!='checked'){
            $og_image_name = $arr['st_og_image'];
        }

        $sql->query(
            $sql->scheme->metaconf('update:metaconf'),
            array(
                $req['og_type'],$req['og_title'],$req['og_description'],$req['og_url'],$og_image_name,$req['naver_verific'],$req['google_verific'],$req['script'],$req['meta']
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
