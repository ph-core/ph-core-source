<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Make\Library\Uploader;
use Manage\Func as Manage;

class submit{

    public function _init(){
        global $req;

        $this->_make();

        switch($req['mode']){
            case 'mod' :
            $this->get_modify();
            break;

            case 'del' :
            $this->get_delete();
            break;
        }
    }

    public function _make(){
        global $req,$file;

        $manage = new Manage();

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','mode,idx,key,title,link,link_target,zindex');
        $file = Method::request('FILE','pc_img,mo_img');
        $manage->req_hidden_inp('POST');
    }

    //////////////////////////////
    // modify
    //////////////////////////////
    public function get_modify(){
        global $req,$file;

        $sql = new Pdosql();
        $uploader = new Uploader();

        $sql->scheme('Manage\\Scheme');

        Valid::isidx('key',$req['key'],1,'');
        Valid::isnum('zindex',$req['zindex'],0,10,1,'');
        Valid::notnull('title',$req['title'],'');

        $uploader->path= PH_DATA_PATH.'/manage';
        $uploader->chkpath();

        $sql->query(
            $sql->scheme->banner('select:banner'),
            array(
                $req['idx']
            )
        );
        $arr = $sql->fetchs('pc_img,mo_img');

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
        if($file['pc_img']['size']>0 && $arr['pc_img']!=''){
            $uploader->drop($arr['pc_img']);
        }
        if($arr['pc_img']!='' && !$file['pc_img']['name']){
            $pc_img_name = $arr['pc_img'];
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
        if($file['mo_img']['size']>0 && $arr['mo_img']!=''){
            $uploader->drop($arr['mo_img']);
        }
        if($arr['mo_img']!='' && !$file['mo_img']['name']){
            $mo_img_name = $arr['mo_img'];
        }

        $sql->query(
            $sql->scheme->banner('update:banner'),
            array(
                $req['key'],$req['title'],$req['link'],$req['link_target'],$pc_img_name,$mo_img_name,$req['zindex'],$req['idx']
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

    //////////////////////////////
    // delete
    //////////////////////////////
    public function get_delete(){
        global $req;

        $sql = new Pdosql();
        $manage = new Manage();
        $uploader = new Uploader();

        $sql->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->banner('select:banner'),
            array(
                $req['idx']
            )
        );
        $arr = $sql->fetchs();

        if($sql->getcount()<1){
            Valid::error('','배너가 존재하지 않습니다.');
        }

        $uploader->path= PH_DATA_PATH.'/manage';
        if($arr['pc_img']){
            $uploader->drop($arr['pc_img']);
        }
        if($arr['mo_img']){
            $uploader->drop($arr['mo_img']);
        }

        $sql->query(
            $sql->scheme->banner('delete:banner'),
            array(
                $req['idx']
            )
        );

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 삭제 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?href=bnlist'.$manage->retlink('')
            )
        );
        Valid::success();
    }

}
