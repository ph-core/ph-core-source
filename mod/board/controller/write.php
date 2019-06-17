<?php
namespace Module\Board;

use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Module\Board\Library as Board_Library;

class Write extends \Controller\Make_Controller{

    static private $show_pwdform = 0;

    public function _init(){
        global $boardconf;

        $this->_func();
        $this->_make();
        if(self::$show_pwdform==0){
            $this->load_tpl(MOD_BOARD_THEME_PATH.'/board/'.$boardconf['theme'].'/write.tpl.php');
        }else{
            $this->load_tpl(MOD_BOARD_THEME_PATH.'/board/'.$boardconf['theme'].'/password.tpl.php');
        }
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','board-writeForm');
        $form->set('type','multipart');
        $form->set('action',MOD_BOARD_DIR.'/controller/write.sbm.php');
        $form->run();
    }

    public function pass_form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','board-pwdForm');
        $form->set('type','static');
        $form->set('target','view');
        $form->set('method','post');
        $form->run();
    }

    public function _func(){
        //category
        function category_option($arr,$category){
            global $boardconf;
            $cat = explode('|',$boardconf['category']);
            $opt = '';
            for($i=0;$i<sizeOf($cat);$i++){
                if(urldecode($cat[$i])==$arr['category'] || urldecode($cat[$i])==$category){
                    $slted = 'selected';
                }else{
                    $slted = '';
                }
                $opt .= '<option value="'.$cat[$i].'" {$slted}>'.$cat[$i].'</option>';
            }
            return $opt;
        }

        //파일명
        function uploaded_file($arr,$wrmode){
            if($wrmode!='reply'){
                $files = array();
                for($i=1;$i<=2;$i++){
                    $files[$i] = $arr['file'.$i];
                }
                return $files;
            }
        }

        //공지글 옵션
        function opt_notice($arr,$wrmode){
            global $MB,$boardconf;
            if($MB['level']==1 || $MB['level']<=$boardconf['ctr_level']){
                if($arr['use_notice']=='Y'){
                    return '<label><input type="checkbox" name="use_notice" id="use_notice" value="checked" checked="checked" />공지글 작성</label>';
                }else{
                    if($arr['rn']>0||$wrmode=='reply'){
                        return '';
                    }else{
                        return '<label><input type="checkbox" name="use_notice" id="use_notice" value="checked" />공지글 작성</label>';
                    }
                }
            }else{
                return '';
            }
        }

        //비밀글 옵션
        function opt_secret($arr){
            global $boardconf;
            if($boardconf['use_secret']=='Y' && ($arr['use_secret']=='Y'||$boardconf['ico_secret_def']=='Y')){
                return '<label><input type="checkbox" name="use_secret" id="use_secret" value="checked" checked="checked" />비밀글 작성</label>';
            }else if($boardconf['use_secret']=='Y'){
                return '<label><input type="checkbox" name="use_secret" id="use_secret" value="checked" />비밀글 작성</label>';
            }else{
                return '';
            }
        }

        //이메일 답변 옵션
        function opt_return_email($arr){
            if($arr['use_email']=='Y'){
                return '<label><input type="checkbox" name="use_email" id="use_email" value="checked" checked="checked" />이메일로 답글 알림 수신</label>';
            }else{
                return '<label><input type="checkbox" name="use_email" id="use_email" value="checked" />이메일로 답글 알림 수신</label>';
            }
        }

        //취소 버튼
        function cancel_btn($page,$category,$where,$keyword){
            return '<a href="?page='.$page.'&category='.$category.'&where='.$where.'&keyword='.urlencode($keyword).'" class="btn2">취소</a>';
        }

        //글쓰기 타이틀
        function write_title($wrmode){
            if($wrmode=='modify'){
                return '글 수정';
            }else if($wrmode=='reply'){
                return '답글 작성';
            }else{
                return '새로운 글 작성';
            }
        }

        //첨부 가능한 파일 사이즈
        function print_filesize(){
            global $func,$boardconf;
            return Func::getbyte($boardconf['file_limit'],'M').'M';
        }
    }

    public function _make(){
        global $MB,$MOD_CONF,$boardconf,$board_id;

        $sql = new Pdosql();
        $boardlib = new Board_Library();

        $sql->scheme('Module\\Board\\Scheme');

        Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');

        $req = Method::request('GET','mode,wrmode,read,page,where,keyword,category');

        $board_id = $MOD_CONF['id'];

        //패스워드가 submit 된 경우
        if(isset($_POST['s_password'])){
            $s_req = Method::request('POST','s_mode,s_wrmode,s_read,s_page,s_category,s_where,s_keyword,s_password');
            $req['mode'] = $s_req['s_mode'];
            $req['wrmode'] = $s_req['s_wrmode'];
            $req['read'] = $s_req['s_read'];
            $req['page'] = $s_req['s_page'];
            $req['category'] = $s_req['s_category'];
            $req['where'] = $s_req['s_where'];
            $req['keyword'] = $s_req['s_keyword'];
        }

        //load config
        $boardconf = $boardlib->load_conf($board_id);

        //add title
        Func::add_title($boardconf['title'].' - 글 작성');

        //add stylesheet & javascript
        $boardlib->print_headsrc($boardconf['theme']);

        //수정 or 답글인 경우 원본 글 불러옴
        if($req['wrmode']=='modify' || $req['wrmode']=='reply'){
            $sql->query(
                $sql->scheme->write('select:data'),
                array(
                    $req['read']
                )
            );
            $arr = $sql->fetchs();
            $sql->specialchars = 0;
            $sql->nl2br = 0;
            $arr['article'] = $sql->fetch('article');

            if($sql->getcount()<1){
                Func::err_back('해당 글이 존재하지 않습니다.');
            }

            if($req['wrmode']=='reply'){
                if($arr['use_html']=='Y'){
                    $arr['article'] = '<br /><br /><br /><div><strong>Org: '.$arr['subject'].'</strong><br />'.$arr['article'].'</div>';
                }else{
                    $arr['article'] = '\n\n\nOrg: '.$arr['subject'].'\n'.$arr['article'];
                }
                $arr['subject'] = 'Re: '.$arr['subject'];
            }
        }else{
            $arr = null;
        }

        //check
        if(!$board_id){
            Func::err_back('게시판이 지정되지 않았습니다.');
        }
        if($MB['level']>$boardconf['write_level'] && $MB['level']>$boardconf['ctr_level']){
            Func::err_back('글 작성 권한이 없습니다.');
        }
        if(!$req['wrmode'] || $req['wrmode']=='reply'){
            if($boardconf['write_point']<0){
                if(!IS_MEMBER){
                    Func::err_back('포인트 설정으로 인해 비회원은 글을 작성할 수 없습니다.');
                }
                if($MB['point']<(0-$boardconf['write_point'])){
                    Func::err_back('포인트가 부족하여 글을 작성할 수 없습니다.');
                }
            }
        }
        if($req['wrmode']=='reply' && $boardconf['use_reply']=='N'){
            Func::err_back('답변글을 등록할 수 없습니다.');
        }

        //삭제된 게시글인지 검사
        if($req['wrmode']=='modify' || $req['wrmode']=='reply'){
            if($arr['dregdate']){
                Func::err_back('삭제된 게시물입니다.');
            }
        }

        //수정모드인 경우 권한 검사
        if($req['wrmode']=='modify'){
            if($MB['level']<=$boardconf['ctr_level']){
                $wr_level = 1;
            }else{
                if($arr['mb_idx']<1 && !IS_MEMBER){
                    $wr_level = 3;
                }else if($arr['mb_idx']==$MB['idx'] && $MB['level']<=$boardconf['write_level']){
                    $wr_level = 1;
                }else{
                    $wr_level = 0;
                }
            }
            if($wr_level==0){
                Func::err_back('수정 권한이 없습니다.');
            }
        }

        //답글 모드인 경우 권한 검사
        if($req['wrmode']=='reply'){
            if(($MB['level']>$boardconf['write_level'] && $MB['level']>$boardconf['ctr_level']) || $MB['level']>$boardconf['reply_level']){
                Func::err_back('답글 작성 권한이 없습니다.');
            }
            if($arr['use_notice']=='Y'){
                Func::err_back('공지글에는 답글을 달 수 없습니다.');
            }
        }

        //패스워드가 submit된 경우 검사
        if(isset($s_req['s_password'])){
            if($arr['pwd']==$s_req['s_password']){
                $wr_level = 1;
            }else{
                Func::err_back('입력한 비밀번호가 일치하지 않습니다.');
            }
        }

        //패스워드 입력 폼 노출
        if($req['wrmode']=='modify' && !IS_MEMBER && $wr_level!=1){

            self::$show_pwdform = 1;

            //작성 폼 노출
        }else{

            self::$show_pwdform = 0;

            if(!IS_MEMBER || ($req['wrmode']=='modify' && $arr['mb_idx']=='0')){
                $is_writer_show = true;
            }else{
                $is_writer_show = false;
            }

            if(!IS_MEMBER || ($req['wrmode']=='modify' && $arr['mb_idx']=='0')){
                $is_pwd_show = true;
                $is_email_show = true;
            }else{
                $is_pwd_show = false;
                $is_email_show = false;
            }

            if(!IS_MEMBER){
                $is_capcha_show = true;
            }else{
                $is_capcha_show = false;
            }

            $is_file_show = array();
            for($i=1;$i<=2;$i++){
                if($boardconf['use_file'.$i]=='Y'){
                    $is_file_show[$i] = true;
                }else{
                    $is_file_show[$i] = false;
                }
                if($arr['file'.$i]!='' && $req['wrmode']!='reply'){
                    $is_filename_show[$i] = true;
                }else{
                    $is_filename_show[$i] = false;
                }
            }

            if($boardconf['use_category']=='Y' && $boardconf['category']!='' && $req['wrmode']!='reply' && $arr['rn']==0 && $arr['reply_cnt']<1){
                $is_category_show = true;
            }else{
                $is_category_show = false;
            }

            if(isset($arr) && !IS_MEMBER && $req['wrmode']!='modify'){
                $arr['pwd'] = '';
                $arr['email'] = '';
            }

            $write = array();
            if(isset($arr)){
                foreach($arr as $key => $value){
                    $write[$key] = $value;
                }
            }else{
                $write = null;
            }
            $this->set('write',$write);
            $this->set('uploaded_file',uploaded_file($arr,$req['wrmode']));
            $this->set('cancel_btn',cancel_btn($req['page'],$req['category'],$req['where'],$req['keyword']));
            $this->set('is_category_show',$is_category_show);
            $this->set('is_writer_show',$is_writer_show);
            $this->set('is_pwd_show',$is_pwd_show);
            $this->set('is_email_show',$is_email_show);
            $this->set('is_file_show',$is_file_show);
            $this->set('is_filename_show',$is_filename_show);
            $this->set('is_capcha_show',$is_capcha_show);

        }
        $this->set('board_id',$board_id);
        $this->set('mode',$req['mode']);
        $this->set('wrmode',$req['wrmode']);
        $this->set('read',$req['read']);
        $this->set('page',$req['page']);
        $this->set('where',$req['where']);
        $this->set('keyword',$req['keyword']);
        $this->set('category',$req['category']);
        $this->set('thisuri',Func::thisuri());
        $this->set('write_title',write_title($req['wrmode']));
        $this->set('category_option',category_option($arr,$req['category']));
        $this->set('opt_notice',opt_notice($arr,$req['wrmode']));
        $this->set('opt_secret',opt_secret($arr));
        $this->set('opt_return_email',opt_return_email($arr));
        $this->set('print_filesize',print_filesize());
        $this->set('top_source',$boardconf['top_source']);
        $this->set('bottom_source',$boardconf['bottom_source']);
    }

}
