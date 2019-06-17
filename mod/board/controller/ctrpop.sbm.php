<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Library\Uploader;
use Make\Database\Pdosql;
use Module\Board\Library as Board_Library;

class Submit{

    public function _init(){
        global $req;

        $this->_make();

        switch($req['type']){
            case 'del' :
            $this->get_del();
            break;

            case 'move' :
            $this->get_move();
            break;

            case 'copy' :
            $this->get_copy();
            break;
        }
    }

    public function _make(){
        global $MB,$boardconf,$req,$cnum,$board_id,$t_board_id;

        $boardlib = new Board_Library();

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','cnum,type,board_id,t_board_id,category,page,where,keyword,thisuri');

        $board_id = $req['board_id'];
        $t_board_id = $req['t_board_id'];

        //load config
        $boardconf = $boardlib->load_conf($board_id);

        //관리 권한 검사
        if($MB['level']>$boardconf['ctr_level']){
            Valid::error('','글을 관리할 권한이 없습니다.');
        }

        //게시물 번호 분리
        $cnum = explode(',',$req['cnum']);
        $cnum = array_reverse($cnum);
    }

    //////////////////////////////
    // 게시물 삭제
    //////////////////////////////
    private function get_del(){
        global $cnum,$req,$board_id,$del_where_sum;

        $uploader = new Uploader();
        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        $del_where = array();

        for($i=0;$i<count($cnum);$i++){
            if($cnum[$i]!=''){

                //원글 게시물 정보
                $sql->query(
                    $sql->scheme->view('select:view2'),
                    array(
                        $cnum[$i]
                    )
                );
                $org_arr = $sql->fetchs();

                //최소/최대 ln값 구함
                $ln_min = (int)(ceil($org_arr['ln']/1000)*1000)-1000;
                $ln_max = (int)(ceil($org_arr['ln']/1000)*1000);

                //부모글인 경우 범위 조건문 구함
                if($org_arr['rn']==0){
                    $del_where[$i] = '(ln>'.$ln_min.' AND ln<='.$ln_max.')';
                }

                //자식글(답글)인 경우 범위 조건문 구함
                if($org_arr['rn']>=1){
                    $sql->query(
                        $sql->scheme->delete('select:ln'),
                        array(
                            $ln_min,$org_arr['ln'],$org_arr['rn']
                        )
                    );
                    $tar_ln = $sql->fetch('ln');
                    if($tar_ln==''){
                        $del_where[$i] = '(ln<='.$org_arr['ln'].' AND ln>'.$ln_min.' AND rn>='.$org_arr['rn'].')';
                    }else{
                        $del_where[$i] = '(ln<='.$org_arr['ln'].' AND ln>'.$tar_ln.' AND rn>='.$org_arr['rn'].')';
                    }
                }
            }
        }

        //삭제 범위 조건문을 하나의 구문으로 합침
        for($i=0;$i<count($del_where);$i++){
            if($i==0){
                $del_where_sum = $del_where[$i];
            }else{
                $del_where_sum .= ' OR '.$del_where[$i];
            }
        }

        //삭제 범위 내 게시물 정보
        $sql->query(
            $sql->scheme->ctrl('select:del_data'),''
        );

        //첨부파일 삭제
        if($sql->getcount()>0){
            do{
                $del_arr = $sql->fetchs();
                for($i=1;$i<=2;$i++){
                    if($del_arr['file'.$i]){
                        $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id.'/';
                        $uploader->drop($del_arr['file'.$i]);
                        $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id.'/thumb/';
                        $uploader->drop($del_arr['file'.$i]);
                    }
                }
            }while($sql->nextRec());
        }

        //댓글 삭제
        if($sql->getcount()>0){
            do{
                $del_arr['idx'] = $sql->fetch('idx');
                $sql->query(
                    $sql->scheme->ctrl('delete:comment'),
                    array(
                        $del_arr['idx']
                    )
                );
            }while($sql->nextRec());
        }

        //게시글 삭제
        $sql->query(
            $sql->scheme->ctrl('delete:data'),''
        );

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'location' => '?page='.$req['page'].'&where='.$req['where'].'&keyword='.$req['keyword'].'&category='.urlencode($req['category']),
                'msg' => '성공적으로 삭제 되었습니다.'
            )
        );
        Valid::success();
    }

    //////////////////////////////
    // 게시물 이동
    //////////////////////////////
    private function get_move(){
        global $cnum,$req,$board_id,$t_board_id,$ln_where;

        $uploader = new Uploader();
        $sql = new Pdosql();
        $cp_sql = new Pdosql();
        $cp_sql2 = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');
        $cp_sql->scheme('Module\\Board\\Scheme');
        $cp_sql2->scheme('Module\\Board\\Scheme');

        //선택된 게시물의 ln,rn 정보
        $ln_where = array();
        for($i=0;$i<count($cnum);$i++){
            if($i==0){
                $ln_where = 'idx=\''.$cnum[$i].'\'';
            }else{
                $ln_where .= ' OR idx=\''.$cnum[$i].'\'';
            }
        }

        $sql->query(
            $sql->scheme->ctrl('select:datas'),''
        );

        $i = 0;
        do{
            $ln_arr = $sql->fetchs();
            $ln[$i] = $ln_arr['ln'];
            $rn[$i] = $ln_arr['rn'];
            $i++;
        }while($sql->nextRec());

        //이동 실행
        for($i=0;$i<count($cnum);$i++){

            //부모글인 경우에만 이동 실행
            if($rn[$i]==0){

                //글의 최소/최대 ln값 구함
                $ln_min = (int)(ceil($ln[$i]/1000)*1000) - 1000;
                $ln_max = (int)(ceil($ln[$i]/1000)*1000);

                //자식글의 범위를 구함
                $ln_where = 'ln>'.$ln_min.' AND ln<='.$ln_max;
                $sql->query(
                    $sql->scheme->ctrl('select:datas'),''
                );

                //대상 게시판의 최대 ln값 불러옴
                $cp_sql->query(
                    $cp_sql->scheme->ctrl('select:ln_max'),''
                );
                $tar_ln = $cp_sql->fetch('ln_max');
                if(!$tar_ln) $tar_ln = 1000;
                $tar_ln = ceil($tar_ln/1000) * 1000;

                //복사 대상 범위에 해당하는 게시물의 이동 시작
                do{
                    $sql->specialchars = 0;
                    $sql->nl2br = 0;
                    $arr = $sql->fetchs();

                    //원본들의 내용을 addslashes 시킴
                    foreach($arr as $key => $value){
                        $arr[$key] = addslashes($arr[$key]);
                    }

                    //대상 게시판으로 첨부파일 복사
                    $old_path = MOD_BOARD_DATA_PATH.'/'.$board_id;
                    $tar_path = MOD_BOARD_DATA_PATH.'/'.$t_board_id;

                    $uploader->path = MOD_BOARD_DATA_PATH;
                    $uploader->chkpath();
                    $uploader->path = $tar_path;
                    $uploader->chkpath();
                    $uploader->path = $tar_path.'/thumb/';
                    $uploader->chkpath();
                    $uploader->path = '';

                    $filename = array();
                    for($fn=1;$fn<=2;$fn++){
                        if($arr['file'.$fn]!=''){
                            $filename[$fn] = $uploader->replace_filename($arr['file'.$fn]);
                            $uploader->filecopy($old_path.'/'.$arr['file'.$fn],$tar_path.'/'.$filename[$fn]);
                            $uploader->filecopy($old_path.'/thumb/'.$arr['file'.$fn],$tar_path.'/thumb/'.$filename[$fn]);
                            $uploader->drop($old_path.'/'.$arr['file'.$fn]);
                            $uploader->drop($old_path.'/thumb/'.$arr['file'.$fn]);
                        }else{
                            $filename[$fn] = '';
                        }
                    }

                    //대상 게시판으로 글을 복사
                    if(!$arr['dregdate']){
                        $cp_sql->query(
                            $cp_sql->scheme->ctrl('insert:cp_data'),
                            array(
                                $arr['category'],$tar_ln,$arr['rn'],$arr['mb_idx'],$arr['mb_id'],$arr['writer'],$arr['pwd'],$arr['email'],$arr['article'],$arr['subject'],$filename[1],$arr['file1_cnt'],$filename[2],$arr['file2_cnt'],$arr['use_secret'],$arr['use_html'],$arr['use_email'],$arr['view'],$arr['ip'],null,$arr['data_1'],$arr['data_2'],$arr['data_3'],$arr['data_4'],$arr['data_5'],$arr['data_6'],$arr['data_7'],$arr['data_8'],$arr['data_9'],$arr['data_10']
                            )
                        );
                    }else{
                        $cp_sql->query(
                            $cp_sql->scheme->ctrl('insert:cp_data'),
                            array(
                                $arr['category'],$tar_ln,$arr['rn'],$arr['mb_idx'],$arr['mb_id'],$arr['writer'],$arr['pwd'],$arr['email'],$arr['article'],$arr['subject'],$filename[1],$arr['file1_cnt'],$filename[2],$arr['file2_cnt'],$arr['use_secret'],$arr['use_html'],$arr['use_email'],$arr['view'],$arr['ip'],$arr['dregdate'],$arr['data_1'],$arr['data_2'],$arr['data_3'],$arr['data_4'],$arr['data_5'],$arr['data_6'],$arr['data_7'],$arr['data_8'],$arr['data_9'],$arr['data_10']
                            )
                        );
                    }

                    //이동된 글의 idx값을 다시 불러옴
                    $cp_sql->query(
                        $cp_sql->scheme->ctrl('select:cped_idx'),
                        array(
                            $tar_ln
                        )
                    );
                    $cped_idx = $cp_sql->fetch('idx');

                    //좋아요 이동
                    $cp_sql->query(
                        $cp_sql->scheme->ctrl('update:move_cmnt'),
                        array(
                            $t_board_id,$cped_idx,$board_id,$arr['idx']
                        )
                    );

                    //댓글 복사를 위해 대상 댓글 테이블의 최대 ln값 구함
                    $cp_sql->query(
                        $cp_sql->scheme->ctrl('select:ln_max'),''
                    );
                    $c_tar_ln = $cp_sql->fetch('ln_max');
                    if(!$c_tar_ln) $c_tar_ln = 1000;
                    $c_tar_ln = ceil($c_tar_ln/1000) * 1000;

                    //댓글 복사를 위해 원본 댓글 테이블의 댓글 정보 가져옴
                    $cp_sql->query(
                        $cp_sql->scheme->ctrl('select:comment'),
                        array(
                            $arr['idx']
                        )
                    );

                    if($cp_sql->getcount()>0){
                        do{
                            $cp_sql->specialchars = 0;
                            $cp_sql->nl2br = 0;
                            $cmt_arr = $cp_sql->fetchs();

                            //가져온 원본들의 내용을 addslashes 시킴
                            foreach($cmt_arr as $key => $value){
                                $cmt_arr[$key] = addslashes($cmt_arr[$key]);
                            }

                            $cp_sql2->query(
                                $cp_sql2->scheme->ctrl('insert:comment'),
                                array(
                                    $cmt_arr['ln'],$cmt_arr['rn'],$cped_idx,$cmt_arr['mb_idx'],$cmt_arr['writer'],$cmt_arr['comment'],$cmt_arr['ip'],$cmt_arr['regdate'],$cmt_arr['cmt_1'],$cmt_arr['cmt_2'],$cmt_arr['cmt_3'],$cmt_arr['cmt_4'],$cmt_arr['cmt_5'],$cmt_arr['cmt_6'],$cmt_arr['cmt_7'],$cmt_arr['cmt_8'],$cmt_arr['cmt_9'],$cmt_arr['cmt_10']
                                )
                            );
                        }while($cp_sql->nextRec());
                    }

                    //기존 댓글 삭제
                    $cp_sql->query(
                        $cp_sql->scheme->ctrl('delete:comment'),
                        array(
                            $arr['idx']
                        )
                    );

                    //원본글 삭제
                    $cp_sql->query(
                        $cp_sql->scheme->delete('delete:data'),
                        array(
                            $arr['idx']
                        )
                    );

                    $tar_ln--;

                }while($sql->nextRec());

            }
        }

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'location' => '?page='.$req['page'].'&where='.$req['where'].'&keyword='.$req['keyword'].'&category='.urlencode($req['category']),
                'msg' => '성공적으로 이동 되었습니다.'
            )
        );
        Valid::success();
    }

    //////////////////////////////
    // 게시물 복사
    //////////////////////////////
    private function get_copy(){
        global $cnum,$req,$board_id,$t_board_id;

        $uploader = new Uploader();
        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        for($i=0;$i<count($cnum);$i++){
            //원본글의 정보를 불러옴
            $sql->query(
                $sql->scheme->view('select:view2'),
                array(
                    $cnum[$i]
                )
            );
            $sql->specialchars = 0;
            $sql->nl2br = 0;
            $arr = $sql->fetchs();

            //원본들의 내용을 addslashes 시킴
            foreach($arr as $key => $value){
                $arr[$key] = addslashes($arr[$key]);
            }

            //부모글인 경우만 복사 실행
            if($arr['rn']==0){

                //대상 게시판의 최대 ln값 불러옴
                $sql->query(
                    $sql->scheme->ctrl('select:ln_max'),''
                );

                $tar_ln = $sql->fetch('ln_max');
                if(!$tar_ln) $tar_ln = 1000;
                $tar_ln = ceil($tar_ln/1000) * 1000;

                //대상 게시판으로 첨부파일 복사
                $old_path = MOD_BOARD_DATA_PATH.'/'.$board_id;
                $tar_path = MOD_BOARD_DATA_PATH.'/'.$t_board_id;

                $uploader->path = MOD_BOARD_DATA_PATH;
                $uploader->chkpath();
                $uploader->path = $tar_path;
                $uploader->chkpath();
                $uploader->path = $tar_path.'/thumb/';
                $uploader->chkpath();
                $uploader->path = '';

                $filename = array();
                for($fn=1;$fn<=2;$fn++){
                    if($arr['file'.$fn]!=''){
                        $fn_re = $uploader->replace_filename($arr['file'.$fn]);
                        $uploader->filecopy($old_path.'/'.$arr['file'.$fn],$tar_path.'/'.$fn_re);
                        $uploader->filecopy($old_path.'/thumb/'.$arr['file'.$fn],$tar_path.'/thumb/'.$fn_re);
                        $filename[$fn] = $fn_re;
                    }else{
                        $filename[$fn] = '';
                    }
                }

                //대상 게시판으로 글을 복사
                if(!$arr['dregdate']){

                    $sql->query(
                        $sql->scheme->ctrl('insert:cp_data'),
                        array(
                            $arr['category'],$tar_ln,$arr['rn'],$arr['mb_idx'],$arr['mb_id'],$arr['writer'],$arr['pwd'],$arr['email'],$arr['article'],$arr['subject'],$filename[1],0,$filename[2],0,$arr['use_secret'],$arr['use_html'],$arr['use_email'],0,$arr['ip'],null,$arr['data_1'],$arr['data_2'],$arr['data_3'],$arr['data_4'],$arr['data_5'],$arr['data_6'],$arr['data_7'],$arr['data_8'],$arr['data_9'],$arr['data_10']
                        )
                    );

                }else{

                    $sql->query(
                        $sql->scheme->ctrl('insert:cp_data'),
                        array(
                            $arr['category'],$tar_ln,$arr['rn'],$arr['mb_idx'],$arr['mb_id'],$arr['writer'],$arr['pwd'],$arr['email'],$arr['article'],$arr['subject'],$filename[1],0,$filename[2],0,$arr['use_secret'],$arr['use_html'],$arr['use_email'],0,$arr['ip'],$arr['dregdate'],$arr['data_1'],$arr['data_2'],$arr['data_3'],$arr['data_4'],$arr['data_5'],$arr['data_6'],$arr['data_7'],$arr['data_8'],$arr['data_9'],$arr['data_10']
                        )
                    );

                }
            }
        }

        //return
        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 복사 되었습니다.'
            )
        );
        Valid::success();
    }

}
