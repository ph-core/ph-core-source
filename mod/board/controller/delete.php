<?php
namespace Module\Board;

use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Uploader;
use Make\Library\Mail;
use Module\Board\Library as Board_Library;

class Delete extends \Controller\Make_Controller{

    static private $show_pwdform = 0;

    public function _init(){
        global $boardconf;

        $this->_make();
        if(self::$show_pwdform==0){
            $this->load_tpl(MOD_BOARD_THEME_PATH.'/board/'.$boardconf['theme'].'/delete.tpl.php');
        }else{
            $this->load_tpl(MOD_BOARD_THEME_PATH.'/board/'.$boardconf['theme'].'/password.tpl.php');
        }
    }

    public function pass_form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','board-pwdForm');
        $form->set('type','static');
        $form->set('target','view');
        $form->set('method','post');
        $form->run();
    }

    public function _make(){
        global $MB,$MOD_CONF,$boardconf,$board_id;

        $sql = new Pdosql();
        $boardlib = new Board_Library();
        $uploader = new Uploader();

        $sql->scheme('Module\\Board\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','read,page,where,keyword,category,s_password');

        $board_id = $MOD_CONF['id'];

        //패스워드가 submit 된 경우
        if(isset($req['s_password'])){
            $s_req = Method::request('POST','s_read,s_page,s_category,s_where,s_keyword');
            $req['read'] = $s_req['s_read'];
            $req['page'] = $s_req['s_page'];
            $req['category'] = $s_req['s_category'];
            $req['where'] = $s_req['s_where'];
            $req['keyword'] = $s_req['s_keyword'];
        }

        //load config
        $boardconf = $boardlib->load_conf($board_id);

        //add stylesheet & javascript
        $boardlib->print_headsrc($boardconf['theme']);

        //chkeck
        if(!$board_id || !$boardconf['id']){
            Func::err_back(ERR_MSG_1);
        }

        //원본 글 정보
        $sql->query(
            $sql->scheme->view('select:view2'),
            array(
                $req['read']
            )
        );
        $arr = $sql->fetchs();

        //권한 check
        if($MB['level']<=$boardconf['ctr_level']){
            $del_level = 1;
        }else{
            if($arr['mb_idx']==0 && !IS_MEMBER && $MB['level']<=$boardconf['delete_level']){
                $del_level = 2;
            }else if($arr['mb_idx']==$MB['idx'] && $MB['level']<=$boardconf['delete_level']){
                $del_level = 1;
            }else{
                $del_level = 0;
            }
        }

        //패스워드가 submit된 경우 패스워드가 일치 하는지 검사
        if(isset($req['s_password'])){
            if($arr['pwd']==$req['s_password']){
                $del_level = 1;
            }else{
                $del_level = 3;
                Func::err_location('비밀번호가 일치하지 않습니다.',PH_DOMAIN.Func::thisuri().'?mode=view&read='.$req['read'].'&page='.$req['page'].'&where='.$req['where'].'&keyword='.$req['keyword'].'&category='.urlencode($req['category']));
            }
        }

        //권한이 없는 경우 경고창
        if($del_level==0){
            Func::arr_back('삭제 권한이 없습니다.');
        }

        //패스워드 입력폼 노출
        if($del_level==2){
            self::$show_pwdform = 1;

            $this->set('mode','delete');
            $this->set('wrmode','');
            $this->set('read',$req['read']);
            $this->set('page',$req['page']);
            $this->set('where',$req['where']);
            $this->set('keyword',$req['keyword']);
            $this->set('category',$req['category']);
            $this->set('top_source',$boardconf['top_source']);
            $this->set('bottom_source',$boardconf['bottom_source']);
        }

        //delete
        if($del_level==1){

            //최소/최대 ln값 구함
            $ln_min = (int)(ceil($arr['ln']/1000)*1000) - 1000;
            $ln_max = (int)(ceil($arr['ln']/1000)*1000);

            //엮인 답글 갯수 구함
            if($arr['rn']<1){

                $sql->query(
                    $sql->scheme->delete('select:child_data'),
                    array(
                        $ln_max,$ln_min,'0'
                    )
                );

            }else{

                $sql->query(
                    $sql->scheme->delete('select:ln'),
                    array(
                        $ln_min,$arr['ln'],$arr['rn']
                    )
                );
                $ln_arr['ln'] = $sql->fetch('ln');

                if($ln_arr['ln']==''){

                    $sql->query(
                        $sql->scheme->delete('select:child_data'),
                        array(
                            $arr['ln'],$ln_min,$arr['rn']
                        )
                    );

                }else{

                    $sql->query(
                        $sql->scheme->delete('select:child_data'),
                        array(
                            $arr['ln'],$ln_arr['ln'],$arr['rn']
                        )
                    );

                }

            }

            $rp_count = $sql->getcount();

            //첨부파일 삭제
            $sql->query(
                $sql->scheme->view('select:view2'),
                array(
                    $req['read']
                )
            );

            do{
                $f_arr = $sql->fetchs();
                for($i=1;$i<=2;$i++){
                    if($f_arr['file'.$i]!=''){
                        $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id;
                        $uploader->drop($f_arr['file'.$i]);
                        $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id.'/thumb/';
                        $uploader->drop($f_arr['file'.$i]);
                    }
                }
            }while($sql->nextRec());

            //댓글 삭제 (엮인 글이 없는 경우)
            if($rp_count<2){
                $bo_idx = $sql->fetch('idx');

                $sql->query(
                    $sql->scheme->delete('delete:comment'),
                    array(
                        $bo_idx
                    )
                );
            }

            //delete (엮인 글이 없는 경우)
            if($rp_count<2){
                $sql->query(
                    $sql->scheme->delete('delete:data'),
                    array(
                        $req['read']
                    )
                );
            }

            //modify (엮인 글이 있는 경우 dragdate만 변경)
            if($rp_count>1){
                $sql->query(
                    $sql->scheme->delete('update:data'),
                    array(
                        $req['read']
                    )
                );
            }

            //return
            Func::location(PH_DOMAIN.Func::thisuri().'?page='.$req['page'].'&where='.$req['where'].'&keyword='.$req['keyword'].'&category='.urlencode($req['category']));
        }
    }

}
