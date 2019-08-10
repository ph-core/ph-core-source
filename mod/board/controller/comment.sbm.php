<?php
use Corelib\Method;
use Corelib\Func;
use Corelib\Valid;
use Make\Database\Pdosql;
use Module\Board\Library as Board_Library;

class Submit{

    public function _init(){
		global $req;

		$this->_make();

		switch($req['mode']){
			case 'write' :
			$this->get_write();
			break;

			case 'reply' :
			$this->get_reply();
			break;

			case 'modify' :
			$this->get_modify();
			break;

			case 'delete' :
			$this->get_delete();
			break;
		}
	}

    public function _make(){
        global $MB,$req,$boardconf,$board_id;

        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        $boardlib = new Board_Library();

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','mode,board_id,read,cidx,writer,comment,captcha,re_writer,re_comment,re_captcha,cmt_1,cmt_2,cmt_3,cmt_4,cmt_5,cmt_6,cmt_7,cmt_8,cmt_9,cmt_10');

        $board_id = $req['board_id'];

        //load config
        $boardconf = $boardlib->load_conf($board_id);

        //원본 글 정보 불러옴
        $sql->query(
            $sql->scheme->view('select:view2'),
            array(
                $req['read']
            )
        );
        $view = $sql->fetchs();

        //chkeck
        if($boardconf['use_comment']=='N'){
            Valid::error('','댓글 기능이 비활성화 중입니다.');
        }
        if($MB['level']>$boardconf['comment_level']){
            Valid::rror('','댓글 사용 권한이 없습니다.');
        }
        if($view['dregdate']){
            Valid::error('','삭제된 게시물의 댓글은 변경할 수 없습니다.');
        }

    }

	//////////////////////////////
	// 새로운 댓글 작성
	//////////////////////////////
    private function get_write(){
        global $MB,$req,$board_id;

        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        //chkeck
        if(IS_MEMBER){
            $mb_idx = $MB['idx'];
            $writer = $MB['name'];
        }else{
            $mb_idx = '';
            Valid::isnick('writer',$req['writer'],1,'');
            $writer = $req['writer'];
            if(!Func::chk_captcha($req['captcha'])){
                Valid::set(
                    array(
                        'return' => 'error',
                        'input' => 'captcha',
                        'err_code' => 'NOTMATCH_CAPTCHA'
                    )
                );
                Valid::success();
            }
        }
        Valid::strLen('comment',$req['comment'],5,'',1,'댓글은 5자 이상 입력해야 합니다.');

        //ln값 처리
        $sql->query(
            $sql->scheme->comment('select:cmt_ln'),
            array(
                $req['read']
            )
        );
        $ln_arr['ln_max'] = $sql->fetch('ln_max');
        if(!$ln_arr['ln_max']) $ln_arr['ln_max']=1000;
        $ln_arr['ln_max'] = (int)floor($ln_arr['ln_max']/1000)*1000;

        //insert
        $sql->query(
            $sql->scheme->comment('insert:comment'),
            array(
                $ln_arr['ln_max'],0,$req['read'],$mb_idx,$writer,$req['comment'],$req['cmt_1'],$req['cmt_2'],$req['cmt_3'],$req['cmt_4'],$req['cmt_5'],$req['cmt_6'],$req['cmt_7'],$req['cmt_8'],$req['cmt_9'],$req['cmt_10']
            )
        );

        //return
        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'view_cmt_load()'
            )
        );
        Valid::success();
    }

	//////////////////////////////
	// 답변 댓글 작성
	//////////////////////////////
    private function get_reply(){
        global $MB,$req,$board_id;

        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        //check
        if(IS_MEMBER){
            $mb_idx = $MB['idx'];
            $writer = $MB['name'];
        }else{
            $mb_idx = '';
            Valid::isnick('re_writer',$req['re_writer'],1,'');
            if(!Func::chk_captcha($req['re_captcha'])){
                Valid::set(
                    array(
                        'return' => 'error',
                        'input' => 're_captcha',
                        'err_code' => 'NOTMATCH_CAPTCHA'
                    )
                );
                Valid::success();
            }
            $writer = $req['re_writer'];
        }
        Valid::strLen('re_comment',$req['re_comment'],5,'',1,'댓글은 5자 이상 입력해야 합니다.');

        //원본 글 정보
        $sql->query(
            $sql->scheme->comment('select:comment'),
            array(
                $req['cidx']
            )
        );
        $bo_idx = (int)$sql->fetch('bo_idx');

        //rn 값 처리
        $rn = (int)$sql->fetch('rn');
        $rn_next = (int)$sql->fetch('rn') + 1;

        //ln값 처리
        $ln = (int)$sql->fetch('ln');
        $ln_min = (int)(floor($ln/1000) * 1000);
        $ln_next = (int)$ln_min + 1000;

        //같은 레벨중 바로 아래 답글의 ln 값을 불러옴
        $sql->query(
            $sql->scheme->comment('select:cmt_ln2'),
            array(
                $ln,$ln_next,$rn,$bo_idx
            )
        );
        $ln_tar = $sql->fetch('ln');

        //댓글의 ln값 부여, 다른 댓글의 ln 정렬
        $sql->query(
            $sql->scheme->comment('select:cmt_ln3'),
            array(
                $ln,$bo_idx,$ln_tar,$rn
            )
        );
        $ln_isrt = $sql->fetch('ln') + 1;
        $sql->query(
            $sql->scheme->comment('update:cmt_ln'),
            array(
                $ln_next,$ln_isrt
            )
        );

        //insert
        $sql->query(
            $sql->scheme->comment('insert:comment'),
            array(
                $ln_isrt,$rn_next,$req['read'],$mb_idx,$writer,$req['re_comment'],$req['cmt_1'],$req['cmt_2'],$req['cmt_3'],$req['cmt_4'],$req['cmt_5'],$req['cmt_6'],$req['cmt_7'],$req['cmt_8'],$req['cmt_9'],$req['cmt_10']
            )
        );

        //return
        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'view_cmt_load()'
            )
        );
        Valid::success();
    }

	//////////////////////////////
	// 댓글 수정
	//////////////////////////////
    private function get_modify(){
        global $MB,$req,$boardconf,$board_id;

        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        //원본 글 정보
        $sql->query(
            $sql->scheme->comment('select:comment'),
            array(
                $req['cidx']
            )
        );
        $arr = $sql->fetchs('mb_idx,writer');

        //check
        if($sql->getcount()<1){
            $this->error('','존재하지 않는 댓글입니다.');
        }
        if($arr['mb_idx']!=$MB['idx'] && $MB['level']>$boardconf['ctr_level'] && $MB['adm']!='Y'){
            Valid::error('','자신의 댓글만 수정 가능합니다.');
        }
        Valid::strLen('re_comment',$req['re_comment'],5,'',1,'댓글은 5자 이상 입력해야 합니다.');

        //update
        if(IS_MEMBER && $arr['mb_idx']==MB_IDX){
            $writer = $MB['name'];
        }else{
            $writer = $arr['writer'];
        }

        $sql->query(
            $sql->scheme->comment('update:comment'),
            array(
                $writer,$req['re_comment'],$req['cmt_1'],$req['cmt_2'],$req['cmt_3'],$req['cmt_4'],$req['cmt_5'],$req['cmt_6'],$req['cmt_7'],$req['cmt_8'],$req['cmt_9'],$req['cmt_10'],$req['cidx']
            )
        );

        //return
        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'view_cmt_load()'
            )
        );
        Valid::success();
    }

	//////////////////////////////
	// 댓글 삭제
	//////////////////////////////
    private function get_delete(){
        global $MB,$req,$boardconf,$board_id;

        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        //chkeck
        $sql->query(
            $sql->scheme->comment('select:comment'),
            array(
                $req['cidx']
            )
        );
        $arr = $sql->fetchs('mb_idx,ln,rn');
        if($sql->getcount()<1){
            Valid::error('','존재하지 않는 댓글입니다.');
        }
        if((!$arr['mb_idx'] || $arr['mb_idx']!=$MB['idx']) && $MB['level']>$boardconf['ctr_level'] && $MB['adm']!='Y'){
            Valid::error('','자신의 댓글이 아니거나, 삭제 권한이 없습니다.');
        }

        //하위 자식 댓글이 있는경우 삭제 금지
        $ln_min = (int)(ceil($arr['ln']/1000)*1000);
        $ln_max = (int)(ceil($arr['ln']/1000)*1000)+1000;

        //부모글인 경우 색인 조건문 만듬
        if($arr['rn']==0){
            $sql->query(
                $sql->scheme->comment('select:chk_cmt'),
                array(
                    $ln_max,$ln_min,0,$req['read']
                )
            );

        //답글이 있는지 검사
        }else if($arr['rn']>=1){

            $sql->query(
                $sql->scheme->comment('select:chk_cmt'),
                array(
                    $ln_max,$arr['ln'],$arr['rn'],$req['read']
                )
            );
            $ln_arr['ln'] = $sql->fetch('ln');

            if($ln_arr['ln']==''){

                $sql->query(
                    $sql->scheme->comment('select:chk_cmt'),
                    array(
                        $ln_max,$arr['ln'],$arr['rn'],$req['read']
                    )
                );

            }else{

                $sql->query(
                    $sql->scheme->comment('select:chk_cmt'),
                    array(
                        $ln_arr['ln'],$arr['ln'],$arr['rn'],$req['read']
                    )
                );

            }
        }

        if($sql->getcount()>1){
            Valid::error('','답글이 있는 경우 삭제가 불가능 합니다.');
        }

        //delete
        $sql->query(
            $sql->scheme->comment('delete:comment'),
            array(
                $req['cidx']
            )
        );

        //return
        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'view_cmt_load()',
            )
        );
        Valid::success();
    }

}
