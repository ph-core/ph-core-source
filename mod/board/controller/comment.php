<?php
namespace Module\Board;

use Corelib\Method;
use Corelib\Func;
use Make\Library\Paging;
use Make\Database\Pdosql;
use Module\Board\Library as Board_Library;

class Comment extends \Controller\Make_Controller{

    public function _init(){
        global $boardconf;

        $this->_func();
        $this->_make();
        $this->load_tpl(MOD_BOARD_THEME_PATH.'/board/'.$boardconf['theme'].'/comment.tpl.php');
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','commentForm');
        $form->set('type','html');
        $form->set('action',MOD_BOARD_DIR.'/controller/comment.sbm.php');
        $form->run();
    }

    public function _func(){
        global $boardconf;

        //삭제 버튼
        function delete_btn($arr,$view){
            global $MB,$boardconf;
            if(($arr['mb_idx']==$MB['idx']&&$arr['mb_idx']!=''&&!$view['dregdate']) || ($MB['level']<$boardconf['ctr_level']&&!$view['dregdate'])){
                return '<a href="#" id="cmt-delete" align="absmiddle" data-cmt-delete="'.$arr['idx'].'"><img src="'.MOD_BOARD_THEME_DIR.'/images/cmt-delete-ico.png" align="absmiddle" title="삭제" alt="삭제" /> 삭제</a>';
            }
        }

        //수정 버튼
        function modify_btn($arr,$view){
            global $MB,$boardconf;
            if(($arr['mb_idx']==$MB['idx']&&$arr['mb_idx']!=''&&!$view['dregdate']) || ($MB['level']<$boardconf['ctr_level']&&!$view['dregdate'])){
                return '<a href="#" id="cmt-modify" data-cmt-modify="'.$arr['idx'].'"><img src="'.MOD_BOARD_THEME_DIR.'/images/cmt-modify-ico.png" align="absmiddle" title="수정" alt="수정" /> 수정</a>';
            }
        }

        //대댓글 버튼
        function reply_btn($arr,$view){
            global $MB,$boardconf;
            if($MB['level']<=$boardconf['comment_level'] && !$view['dregdate']){
                return '<a href="#" id="cmt-reply" data-cmt-reply="'.$arr['idx'].'"><img src="'.MOD_BOARD_THEME_DIR.'/images/cmt-reply-ico.png" align="absmiddle" title="답변 댓글 작성" alt="답변 댓글 작성" /> 답글</a>';
            }
        }

        //회원 이름
        function print_writer($arr){
            if($arr['mb_idx']!=0){
                return '<a href="#" data-profile="'.$arr['mb_idx'].'">'.$arr['writer'].'</a>';
            }else{
                return $arr['writer'];
            }
        }

        //대댓글인 경우 들여쓰기 클래스 부여
        function reply_class($arr){
            if($arr['rn']>0){
                return 'dep-'.$arr['rn'];
            }
        }
    }

    public function _make(){
        global $MB,$boardconf,$board_id;

        $sql = new Pdosql();
        $paging = new Paging();
        $boardlib = new Board_Library();

        $sql->scheme('Module\\Board\\Scheme');

        $req = Method::request('GET','board_id,read');

        $board_id = $req['board_id'];

        $boardconf = $boardlib->load_conf($board_id);

        //원본 글 정보 불러옴
        $sql->query(
            $sql->scheme->view('select:view2'),
            array(
                $req['read']
            )
        );
        $view = $sql->fetchs();

        if($boardconf['use_comment']=='Y'){

            if(IS_MEMBER){
                $type = 2;
            }else{
                $type = 1;
            }

            if($MB['level']<=$boardconf['comment_level'] && !$view['dregdate']){
                $is_writeform_show = true;

                if(!IS_MEMBER){
                    $is_writer_show = true;
                }else{
                    $is_writer_show = false;
                }
            }else{
                $is_writeform_show = false;
                $is_writer_show = false;
            }

            if(!IS_MEMBER){
                $is_guest_form_show = true;
            }else{
                $is_guest_form_show = false;
            }

            //list
            $sql->query(
                $sql->scheme->comment('select:list'),
                array(
                    $req['read']
                )
            );
            $total_cnt = Func::number($sql->getcount());
            $print_arr = array();

            if($total_cnt>0){
                do{
                    $arr = $sql->fetchs();

                    $arr['date'] = Func::date($arr['regdate']);
                    $arr['datetime'] = Func::datetime($arr['regdate']);
                    $arr[0]['reply_class'] = reply_class($arr);
                    $arr[0]['reply_btn'] = reply_btn($arr,$view);
                    $arr[0]['modify_btn'] = modify_btn($arr,$view);
                    $arr[0]['delete_btn'] = delete_btn($arr,$view);
                    $arr[0]['writer'] = print_writer($arr);

                    $print_arr[] = $arr;

                }while($sql->nextRec());
            }

            $this->set('is_writeform_show',$is_writeform_show);
            $this->set('is_writer_show',$is_writer_show);
            $this->set('is_guest_form_show',$is_guest_form_show);
            $this->set('print_arr',$print_arr);
            $this->set('board_id',$board_id);
            $this->set('read',$req['read']);
            $this->set('total_cnt',$total_cnt);
        }
    }

}
