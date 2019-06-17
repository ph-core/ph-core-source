<?php
use Corelib\Method;
use Corelib\Valid;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Uploader;
use Manage\Func as Manage;

class submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        $manage = new Manage();
        $sql = new Pdosql();
        $uploader = new Uploader();

        $sql->scheme('Manage\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','key,title,link,link_target,zindex');
        $file = Method::request('FILE','pc_img,mo_img');
        $manage->req_hidden_inp('POST');

        Valid::isidx('key',$req['key'],1,'');
        Valid::isnum('zindex',$req['zindex'],0,10,1,'');
        Valid::notnull('title',$req['title'],'');

        if(!$file['pc_img']['name'] || !$file['mo_img']['name']){
            Valid::error('','배너 이미지가 첨부되지 않았습니다.');
        }

        $uploader->path= PH_DATA_PATH.'/manage';
        $uploader->chkpath();

        $pc_img_name = '';
        if($file['pc_img']['size']>0){
            $uploader->file = $file['pc_img'];
            $uploader->intdict = SET_IMGTYPE;
            if($uploader->chkfile('match')!==true){
                Valid::error('pc_img','허용되지 않는 PC 배너 이미지 유형입니다.');
            }
            $pc_img_name = $uploader->replace_filename($file['pc_img']['name']);
            if(!$uploader->upload($pc_img_name)){
                Valid::error('pc_img','PC 배너 이미지 업로드 실패');
            }
        }

        $mo_img_name = '';
        if($file['mo_img']['size']>0){
            $uploader->file = $file['mo_img'];
            $uploader->intdict = SET_IMGTYPE;
            if($uploader->chkfile('match')!==true){
                Valid::error('mo_img','허용되지 않는 모바일 배너 이미지 유형입니다.');
            }
            $mo_img_name = $uploader->replace_filename($file['mo_img']['name']);
            if(!$uploader->upload($mo_img_name)){
                Valid::error('mo_img','모바일 배너 이미지 업로드 실패');
            }
        }

        $sql->query(
            $sql->scheme->banner('insert:banner'),
            array(
                $req['key'],$req['title'],$req['link'],$req['link_target'],$pc_img_name,$mo_img_name,$req['zindex']
            )
        );

        $sql->query(
            $sql->scheme->banner('select:banner2'),
            array(
                $req['key']
            )
        );
        $idx = $sql->fetch('idx');

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 추가 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?href=bnlist&p=modifybn&idx='.$idx
            )
        );
        Valid::success();
    }

}
