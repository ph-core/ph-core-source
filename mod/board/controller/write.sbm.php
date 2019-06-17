<?php
use Corelib\Method;
use Corelib\Func;
use Corelib\Valid;
use Corelib\Session;
use Make\Library\Uploader;
use Make\Library\Imgresize;
use Make\Library\Mail;
use Make\Database\Pdosql;
use Module\Board\Library as Board_Library;

class Submit{

    public function _init(){
        global $req;

        $this->_make();

        switch($req['wrmode']){
            case 'reply' :
            $this->get_reply();
            break;

            case 'modify' :
            $this->get_modify();
            break;

            default :
            $this->get_write();
            break;
        }
    }

    public function _make(){
        global $MB,$boardconf,$req,$ufile,$wr_opt,$org_arr,$board_id;

        include_once PH_PLUGIN_PATH.'/capcha/zmSpamFree.php';

        $boardlib = new Board_Library();
        $uploader = new Uploader();
        $imgresize = new Imgresize();
        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','thisuri,board_id,wrmode,read,page,where,keyword,category_ed,use_html,category,use_notice,use_secret,use_email,writer,password,email,subject,article,file1_del,file2_del,capcha,data_1,data_2,data_3,data_4,data_5,data_6,data_7,data_8,data_9,data_10');
        $f_req = Method::request('FILE','file1,file2');

        $board_id = $req['board_id'];

        //load config
        $boardconf = $boardlib->load_conf($board_id);

        //수정 or 답글인 경우 원본 글 가져옴
        if($req['wrmode']=='modify' || $req['wrmode']=='reply'){
            $sql->query(
                $sql->scheme->write('select:data'),
                array(
                    $req['read']
                )
            );
            $org_arr = $sql->fetchs();
        }

        //수정 or 답글인 경우 삭제된 게시글인지 검사
        if($req['wrmode']=='modify' || $req['wrmode']=='reply'){
            if($org_arr['dregdate']){
                Func::err_back('삭제된 게시글입니다.');
            }
        }

        //옵션값 처리
        $wr_opt = array();
        if($req['use_notice']=='checked'){
            $wr_opt['notice'] = 'Y';
            $wr_opt['email'] = 'N';
        }else{
            $wr_opt['notice'] = 'N';
        }
        if($boardconf['use_secret']=='Y'){
            if($req['use_secret']=='checked'){
                $wr_opt['secret'] = 'Y';
            }else{
                $wr_opt['secret'] = 'N';
            }
        }else if(!$wrmode){
            $wr_opt['secret'] = 'N';
        }else{
            $wr_opt['secret'] = $org_arr['use_secret'];
        }
        if($req['use_email']=='checked'){
            $wr_opt['email'] = 'Y';
        }else{
            $wr_opt['email'] = 'N';
        }

        //수정모드인 경우 여분필드 처리
        if($req['wrmode']=='modify'){
            for($i=1;$i<=10;$i++){
                if(!$req['data_'.$i]){
                    $req['data_'.$i] = $org_arr['data_'.$i];
                }
            }
        }

        //check
        if($MB['level']>$boardconf['write_level'] && $MB['level']>$boardconf['ctr_level']){
            $this->error('','글 작성 권한이 없습니다.');
        }
        Valid::notnull('subject',$req['subject'],'');
        Valid::strLen('article',$req['article'],$boardconf['article_min_len'],'',1,'내용은 '.$boardconf['article_min_len'].'자 이상 입력해야 합니다.');

        if(!IS_MEMBER){
            Valid::isnick('writer',$req['writer'],1,'');
            Valid::ispwd('password',$req['password'],1,'');
            if($wr_opt['email']=='Y'){
                Valid::isemail('email',$req['email'],1,'');
            }
            if(zsfCheck($req['capcha'],'')!=true){
                Valid::set(
                    array(
                        'return' => 'error',
                        'input' => 'capcha',
                        'err_code' => 'NOTMATCH_CAPCHA'
                    )
                );
                Valid::success();
            }
        }

        if($f_req['file1']['size']>0 && $f_req['file2']['size']>0 && $f_req['file1']['name']==$f_req['file2']['name']){
            $this->error('','동일한 파일을 업로드 할 수 없습니다.');
        }
        Valid::chktag('article',$req['article'],1,'');

        //수정모드인 경우 검사
        if($req['wrmode']=='modify' && IS_MEMBER && $org_arr['mb_idx']==0){
            Valid::isnick('writer',$req['writer'],1,'');
            Valid::ispwd('password',$req['password'],1,'');
            if($wr_opt['email']=='Y' || $req['email']!=''){
                Valid::isemail('email',$req['email'],1,'');
            }
        }

        //글 작성인 경우, 이미 같은 내용의 글이 존재하는지 검사
        if(!$req['wrmode'] || $req['wrmode']=='reply'){
            $sql->query(
                $sql->scheme->write('select:chk_article'),
                array(
                    $req['article']
                )
            );
            if($sql->getcount()>0){
                Valid::error('article','이미 같은 내용의 글이 존재합니다.');
            }
        }

        //글 본문에 사용 금지 태그가 있는지 검사
        if(Func::chkintd('match',$req['article'],SET_INTDICT_TAGS)==true){
            Valid::error('article',ERR_MSG_2);
        }

        //글 작성 포인트 조정
        if(!$req['wrmode'] || $req['wrmode']=='reply'){
            if($boardconf['write_point']<0){
                if(!IS_MEMBER){
                    $this->error('','포인트 설정으로 인해 비회원은 글을 작성할 수 없습니다.');
                }
                if($MB['point']<(0-$boardconf['write_point'])){
                    $this->error('','포인트가 부족하여 글을 작성할 수 없습니다.');
                }
                $point = 0 - $boardconf['write_point'];
                Func::set_mbpoint($MB['idx'],'out',$point,'게시판 글 작성 ('.$boardconf['title'].')');
            }else if($boardconf['write_point']>0){
                Func::set_mbpoint($MB['idx'],'in',$boardconf['write_point'],'게시판 글 작성 ('.$boardconf['title'].')');
            }
        }

        //첨부파일 저장
        $uploader->path = MOD_BOARD_DATA_PATH;
        $uploader->chkpath(); //모듈 data 폴더 검사
        $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id;
        $uploader->chkpath(); //게시판별 폴더 검사

        $ufile = array();
        $ufile_name = array();

        for($i=1;$i<=2;$i++){
            $ufile[$i] = $f_req['file'.$i];
        }

        for($i=1;$i<=2;$i++){
            if($ufile[$i]['size']>0 && $boardconf['use_file'.$i]){
                $uploader->file = $ufile[$i];
                if($uploader->chkfile('match')===true){
                    Valid::error('file'.$i,ERR_MSG_8);
                }
                if($uploader->chkbyte($boardconf['file_limit'])===false){
                    Valid::error('file'.$i,'허용 파일 용량을 초과합니다.');
                }
                $ufile[$i]['ufile_name'] = $uploader->replace_filename($ufile[$i]['name']);
                array_push($ufile_name,$ufile[$i]['ufile_name']);

                if(!$uploader->upload($ufile[$i]['ufile_name'])){
                    Valid::error('file'.$i,'첨부파일'.$i.' 업로드 실패');
                }
            }
        }

        //썸네일 생성
        $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id.'/thumb/';
        $uploader->chkpath();

        for($i=0;$i<count($ufile_name);$i++){
            $intd = explode(',',SET_IMGTYPE);
            $f_type = Func::get_filetype($ufile_name[$i]);
            for($j=0;$j<=count($intd)-1;$j++){
                if($f_type==$intd[$j]){
                    $imgresize->set(
                        array(
                            'orgimg' => MOD_BOARD_DATA_PATH.'/'.$board_id.'/'.$ufile_name[$i],
                            'newimg' => $uploader->path.'/'.$ufile_name[$i],
                            'width' => 800
                        )
                    );
                    $imgresize->make();
                }
            }
        }

        //수정모드인 경우 기존 파일 & 썸네일 삭제
        if($req['wrmode']=='modify'){
            for($i=1;$i<=2;$i++){

                //기존 파일을 삭제할 때
                if($req['file'.$i.'_del']=='checked' || ($ufile[$i]['size']>0 && $org_arr['file'.$i] && $req['file'.$i.'_del']!='checked')){
                    $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id;
                    $uploader->drop($org_arr['file'.$i]);
                    $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id.'/thumb/';
                    $uploader->drop($org_arr['file'.$i]);
                }

                //아무것도 하지 않았을 때
                if($org_arr['file'.$i]!='' && !$ufile[$i]['tmp_name'] && $req['file'.$i.'_del']!='checked'){
                    $ufile[$i]['ufile_name'] = $org_arr['file'.$i];
                }
            }
        }
    }

    //////////////////////////////
    // 새로운 글 작성
    //////////////////////////////
    private function get_write(){
        global $MODULE_BOARD_CONF,$MB,$boardconf,$req,$ufile,$wr_opt,$board_id;

        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        //ln값 처리
        $sql->query(
            $sql->scheme->write('select:ln'),''
        );

        $ln_arr = array();
        $ln_arr['ln_max'] = $sql->fetch('ln_max');
        if(!$ln_arr['ln_max']){
            $ln_arr['ln_max'] = 1000;
        }
        $ln_arr['ln_max'] = ceil($ln_arr['ln_max']/1000)*1000;

        //회원인 경우 회원 정보를 필드에 입력
        if(IS_MEMBER){
            $req['email'] = $MB['email'];
            $req['writer'] = $MB['name'];
        }

        //insert
        $sql->query(
            $sql->scheme->write('insert:data'),
            array(
                $req['category'],$MB['idx'],$MB['id'],$req['writer'],$req['password'],$req['email'],$req['article'],$req['subject'],$ufile[1]['ufile_name'],$ufile[2]['ufile_name'],$wr_opt['secret'],$wr_opt['notice'],$wr_opt['email'],$ln_arr['ln_max'],0,$req['data_1'],$req['data_2'],$req['data_3'],$req['data_4'],$req['data_5'],$req['data_6'],$req['data_7'],$req['data_8'],$req['data_9'],$req['data_10']
            )
        );

        //작성된 글의 idx
        $sql->query(
            $sql->scheme->write('select:write_idx'),
            array(
                $req['writer'],$req['subject'],$req['article']
            )
        );

        //관리자 Dashboard 소식 등록
        if($boardconf['use_mng_feed']=='Y'){
            Func::add_mng_feed(
                array(
                    $MODULE_BOARD_CONF['title'],
                    '<strong>'.$req['writer'].'</strong>님이 <strong>'.$boardconf['title'].'</strong> 게시판에 새로운 글을 등록했습니다.',
                    $req['thisuri'].'?mode=view&read='.$sql->fetch('idx')
                )
            );
        }

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'location' => $req['thisuri'].'?mode=view&read='.$sql->fetch('idx').'&category='.urlencode($req['category_ed'])
            )
        );
        Valid::success();
    }

    //////////////////////////////
    // 글 수정
    //////////////////////////////
    private function get_modify(){
        global $MB,$req,$org_arr,$ufile,$wr_opt,$board_id;

        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        //공지사항 옵션 체크한 경우 답글이 있는지
        if($req['use_notice']=='checked'){
            //최소/최대 ln값 구함
            $ln_min = (int)(ceil($org_arr['ln']/1000)*1000)-1000;
            $ln_max = (int)(ceil($org_arr['ln']/1000)*1000);
            $sql->query(
                $sql->scheme->write('select:min_ln'),
                array(
                    $ln_min,
                    $ln_max
                )
            );
            if($sql->getCount()>1){
                $this->error('','답글이 있는 게시글은 공지사항 옵션을 사용할 수 없습니다.');
            }
        }

        //Category 처리
        if($org_arr['reply_cnt']>0){
            $category = $org_arr['category'];
        }else{
            $category = $req['category'];
        }

        //writer 처리
        if($org_arr['mb_idx']==$MB['idx'] && IS_MEMBER){
            $req['writer'] = $MB['name'];
        }else if($org_arr['mb_idx']!=0 && IS_MEMBER){
            $req['writer'] = $org_arr['writer'];
        }

        //email & password 처리
        if(IS_MEMBER && $org_arr['mb_idx']!=0){
            $req['email'] = $org_arr['email'];
            $req['password'] = $org_arr['pwd'];
        }

        //update
        $sql->query(
            $sql->scheme->write('update:data'),
            array(
                $category,$req['writer'],$req['password'],$req['email'],$req['article'],$req['subject'],$ufile[1]['ufile_name'],$ufile[2]['ufile_name'],$wr_opt['secret'],$wr_opt['notice'],$wr_opt['email'],$req['data_1'],$req['data_2'],$req['data_3'],$req['data_4'],$req['data_5'],$req['data_6'],$req['data_7'],$req['data_8'],$req['data_9'],$req['data_10'],
                $req['read']
            )
        );

        //조회수 session
        Session::set_sess('BOARD_VIEW_'.$req['read'],$req['read']);

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'location' => $req['thisuri'].'?mode=view&read='.$req['read'].'&page='.$req['page'].'&where='.$req['where'].'&keyword='.$req['keyword'].'&category='.urlencode($req['category_ed'])
            )
        );
        Valid::success();
    }

    //////////////////////////////
    // 답글 작성
    //////////////////////////////
    private function get_reply(){
        global $MODULE_BOARD_CONF,$MB,$boardconf,$req,$org_arr,$ufile,$wr_opt,$board_id;

        $sql = new Pdosql();
        $mail = new Mail();

        $sql->scheme('Module\\Board\\Scheme');

        //ln값 처리
        $ln_max = (int)$org_arr['ln'];
        $ln_min = (int)(ceil($org_arr['ln']/1000)*1000)-1000;
        $ln_me = (int)$org_arr['ln']-1;

        $sql->query(
            $sql->scheme->write('update:ln'),
            array(
                $ln_max,$ln_min
            )
        );

        //rn값 처리
        $sql->query(
            $sql->scheme->write('select:rn'),
            array(
                $req['read']
            )
        );

        $rn_arr = array();
        $rn_arr['rn_max'] = $sql->fetch('rn_max');

        //회원인 경우 정보를 필드에 기록
        if(IS_MEMBER){
            $req['email'] = $MB['email'];
            $req['writer'] = $MB['name'];
        }

        //비회원의 비밀글에 대한 답글인 경우 원본글의 비밀번호를 기록
        if($org_arr['use_secret']=='Y' && $org_arr['mb_idx']==0){
            $req['password'] = $org_arr['pwd'];
        }

        //insert
        $sql->query(
            $sql->scheme->write('insert:data'),
            array(
                $org_arr['category'],$MB['idx'],$MB['id'],$req['writer'],$req['password'],$req['email'],$req['article'],$req['subject'],$ufile[1]['ufile_name'],$ufile[2]['ufile_name'],$wr_opt['secret'],$wr_opt['notice'],$wr_opt['email'],$ln_me,$rn_arr['rn_max'],$req['data_1'],$req['data_2'],$req['data_3'],$req['data_4'],$req['data_5'],$req['data_6'],$req['data_7'],$req['data_8'],$req['data_9'],$req['data_10']
            )
        );

        //작성된 글의 idx
        $sql->query(
            $sql->scheme->write('select:write_idx'),
            array(
                $req['writer'],$req['subject'],$req['article']
            )
        );

        //원본글이 답글 이메일 수신 옵션이 켜져 있는 경우 원본글 작성자에게 메일 발송
        if($org_arr['use_email']=='Y'){
            $memo = '
                <strong>'.$boardconf['title'].'</strong>에 게시한<br /><br />
                회원님의 게시글에 답글이 달렸습니다.<br />
                아래 주소를 클릭하여 확인 할 수 있습니다.<br /><br />

                <a href=\''.PH_DOMAIN.$req['thisuri'].'?mode=view&read='.$sql->fetch('idx').'&category='.urlencode($req['category_ed']).'\'>'.PH_DOMAIN.$req['thisuri'].'?mode=view&read='.$sql->fetch('idx').'&category='.urlencode($req['category_ed']).'</a>
            ';
            $mail->set(
                array(
                    't_email' => $org_arr['email'],
                    't_name' => $boardconf['title'],
                    'subject' => '회원님의 게시글에 답글이 달렸습니다.',
                    'memo' => str_replace('\"','"',stripslashes($memo)),
                )
            );
            $mail->send();
        }

        //조회수 session
        Session::set_sess('BOARD_VIEW_'.$sql->fetch('idx'),$sql->fetch('idx'));

        //관리자 최근 피드에 등록
        if($boardconf['use_mng_feed']=='Y'){
            Func::add_mng_feed(
                array(
                    $MODULE_BOARD_CONF['title'],
                    '<strong>'.$req['writer'].'</strong>님이 <strong>'.$boardconf['title'].'</strong> 게시판에 새로운 답글을 등록했습니다.',
                    $req['thisuri'].'?mode=view&read='.$sql->fetch('idx')
                )
            );
        }

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'location' => $req['thisuri'].'?mode=view&read='.$sql->fetch('idx').'&page='.$req['page'].'&where='.$req['where'].'&keyword='.$req['keyword'].'&category='.urlencode($req['category_ed'])
            )
        );
        Valid::success();
    }

}
