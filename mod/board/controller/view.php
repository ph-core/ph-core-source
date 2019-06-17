<?php
namespace Module\Board;

use Corelib\Method;
use Corelib\Func;
use Corelib\Session;
use Make\Database\Pdosql;
use Module\Board\Library as Board_Library;

class View extends \Controller\Make_Controller{

    static private $show_pwdform = 0;

    public function _init(){
        global $boardconf;

        $this->_func();
        $this->_make();
        if(self::$show_pwdform==0){
            $this->load_tpl(MOD_BOARD_THEME_PATH.'/board/'.$boardconf['theme'].'/view.tpl.php');
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

    public function likes_form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','board-likes');
        $form->set('type','html');
        $form->set('action',MOD_BOARD_DIR.'/controller/likes.sbm.php');
        $form->run();
    }

    public function _func(){
        //비밀글 아이콘 출력
        function secret_ico($arr){
            if($arr['use_secret']=='Y'){
                return '<img src=\''.MOD_BOARD_THEME_DIR.'/images/secret-ico.png\' align=\'absmiddle\' title=\'비밀글\' alt=\'비밀글\' />';
            }
        }

        //삭제 버튼
        function delete_btn($arr){
            global $MB,$boardconf;
            echo $arr['dregdate'];
            if($MB['level']<=$boardconf['ctr_level'] && !$arr['dregdate']){
                $is_btn_show = true;
            }else if($arr['mb_idx']=='0' && !IS_MEMBER && $MB['level']<=$boardconf['delete_level'] && !$arr['dregdate']){
                $is_btn_show = true;
            }else if($arr['mb_idx']==$MB['idx'] && $MB['level']<=$boardconf['delete_level'] && !$arr['dregdate']){
                $is_btn_show = true;
            }else{
                $is_btn_show = false;
            }
            if($is_btn_show){
                return '<button type=\'button\' class=\'btn2\' id=\'del-btn\'><i class=\'fa fa-trash-alt\'></i> 삭제</button>';
            }
        }

        //수정 버튼
        function modify_btn($arr,$read,$page,$keyword,$where,$category){
            global $MB,$boardconf;
            if($MB['level']<=$boardconf['ctr_level'] && !$arr['dregdate']){
                $is_btn_show = true;
            }else if($arr['mb_idx']=='0' && !IS_MEMBER && $boardconf['write_level']==10 && !$arr['dregdate']){
                $is_btn_show = true;
            }else if($arr['mb_idx']==$MB['idx'] && $MB['level']<=$boardconf['write_level'] && !$arr['dregdate']){
                $is_btn_show = true;
            }else{
                $is_btn_show = false;
            }
            if($is_btn_show){
                return '<a href=\'?mode=write&wrmode=modify&category='.urlencode($category).'&read='.$read.'&page='.$page.'&where='.$where.'&keyword='.urlencode($keyword).'\' class=\'btn1\'>수정</a>';
            }
        }

        //답글 버튼
        function reply_btn($arr,$read,$page,$keyword,$where,$category){
            global $MB,$boardconf;
            if(($MB['level']>$boardconf['write_level'] && $MB['level']>$boardconf['ctr_level']) || $arr['use_notice']=='Y' || $boardconf['use_reply']=='N' || $MB['level']>$boardconf['reply_level'] || $arr['dregdate']!=''){
                $is_btn_show = false;
            }else{
                $is_btn_show = true;
            }
            if($is_btn_show){
                return '<a href="?mode=write&wrmode=reply&category='.urlencode($category).'&read='.$read.'&page='.$page.'&where='.$where.'&keyword='.urlencode($keyword).'" class="btn1"><i class="fa fa-pen"></i> 답글</a>';
            }
        }

        //리스트 버튼
        function list_btn($page,$keyword,$where,$category){
            return '<a href="?category='.urlencode($category).'&page='.$page.'&where='.$where.'&keyword='.urlencode($keyword).'" class="btn1"><i class="fa fa-bars"></i> 리스트</a>';
        }

        //첨부 이미지 출력
        function print_imgfile($arr){
            global $boardconf;
            $files = array();
            for($i=1;$i<=2;$i++){
                $file_type = Func::get_filetype($arr['file'.$i]);
                if(Func::chkintd('match',$file_type,SET_IMGTYPE)){
                    $files[$i] = '<img src=\''.PH_DOMAIN.MOD_BOARD_DATA_DIR.'/'.$boardconf['id'].'/thumb/'.$arr['file'.$i].'\' alt=\'첨부된 이미지파일\' />';
                }else{
                    $files[$i] = null;
                }
            }
            return $files;
        }

        //첨부파일명 및 용량(Byte) 출력
        function print_file_name($arr){
            global $boardconf;
            $files = array();
            for($i=1;$i<=2;$i++){
                if($arr['file'.$i]){
                    $files[$i] = '
                    <a href=\''.MOD_BOARD_DIR.'/controller/filedown?board_id='.$boardconf['id'].'&file='.urlencode($arr['file'.$i]).'&OUTLOAD=1\' target=\'_blank\'>'.Func::strcut($arr['file'.$i],0,70).'</a>
                    <span class=\'byte\'>('.Func::filesize(MOD_BOARD_DATA_PATH.'/'.$boardconf['id'].'/'.$arr['file'.$i],'K').'K)</span>
                    <span class=\'cnt\'><strong>'.Func::number($arr['file'.$i.'_cnt']).'</strong> 회 다운로드</span>
                    ';
                }else{
                    $files[$i] = null;
                }
            }
            return $files;
        }

        //회원 이름
        function print_writer($arr){
            if($arr['mb_idx']!=0){
                return '<a href="#" data-profile="'.$arr['mb_idx'].'">'.$arr['writer'].'</a>';
            }else{
                return $arr['writer'];
            }
        }
    }

    public function _make(){
        global $MB,$MOD_CONF,$boardconf,$board_id;

        $sql = new Pdosql();
        $sess = new Session();
        $boardlib = new Board_Library();

        $sql->scheme('Module\\Board\\Scheme');

        $req = Method::request('GET','mode,wrmode,read,page,where,keyword,category');

        if(isset($_POST['s_password'])){
            $s_req = Method::request('POST','s_password');
        }

        $board_id = $MOD_CONF['id'];

        //패스워드가 submit 된 경우
        if(isset($s_req['s_password'])){
            $s_req = $method->request('POST','s_password,s_read,s_page,s_category,s_where,s_keyword');
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

        //load session
        $view_sess = $sess->sess('BOARD_VIEW_'.$req['read']);

        //원본 글 불러옴
        $sql->query(
            $sql->scheme->view('select:view'),
            array(
                $req['read']
            )
        );
        if($sql->getcount()<1){
            Func::err_back('해당 글이 존재하지 않습니다.');
        }
        $arr = $sql->fetchs();
        $sql->specialchars = 0;
        $sql->nl2br = 0;
        $arr['article'] = $sql->fetch('article');
        foreach($sql->array as $key => $value){
            $boardconf[$key] = $value;
        }

        //add title
        Func::add_title($boardconf['title'].' - '.$arr['subject']);

        //add stylesheet
        Func::add_stylesheet(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/contents_view.css');

        //게시물이 답글이며 회원에 대한 답글인 경우 부모글의 회원 idx 가져옴
        if($arr['rn']>0 && $arr['pwd']==''){
            $sql->query(
                $sql->scheme->view('select:parent_mb_idx'),
                array(
                    $arr['ln'],
                    $arr['rn'] - 1
                )
            );
            $prt_mb_idx = $sql->fetch('mb_idx');
        }

        //패스워드가 submit된 경우(비밀글) 패스워드가 일치 하는지 검사
        if(isset($s_req['s_password'])){
            if($arr['pwd']==$s_req['s_password']){
                $rd_level = 1;
            }else{
                $rd_level = 3;
                Func::err_back('비밀번호가 일치하지 않습니다.');
            }
        }

        //패스워드 submit이 아닌 경우, 글 읽기 권한이 있는지 검사
        if(!isset($s_req['s_password'])){

            //비밀글인 경우
            if($arr['use_secret']=='Y'){

                //관리자 레벨 이거나, 비밀글 읽기 권한이 있는 경우 글을 보임
                if($MB['level']<=$boardconf['ctr_level'] || $MB['level']<=$boardconf['secret_level']){
                    $rd_level = 1;
                    //그 외
                }else{
                    //비회원의 글이고 로그인 되지 않은 경우 패스워드 폼을 보임
                    if($arr['mb_idx']==0 && !IS_MEMBER){
                        $rd_level = 3;
                        //글이 답글이고, 비밀번호가 저장되어 있는 경우(비회원 글에 대한 답변) 패스워드 폼을 보임
                    }else if($arr['rn']>0 && $arr['pwd']!='' && !IS_MEMBER){
                        $rd_level = 3;
                        //글이 답글이고, 자신의 글에 대한 답글인 경우 글을 보임
                    }else if($arr['rn']>0 && $prt_mb_idx==$MB['idx']){
                        $rd_level = 1;
                        //자신의 글인 경우 글을 보임
                    }else if($arr['mb_idx']==$MB['idx']){
                        $rd_level = 1;
                        //그 외 아무 권한 없음
                    }else{
                        $rd_level = 0;
                    }
                }

                //비밀글이 아닌 경우
            }else if($arr['use_secret']=='N'){
                //글 읽기 권한이 있는 경우 글을 보임
                if($MB['level']<=$boardconf['read_level']){
                    $rd_level = 1;
                //그 외
                }else{
                    //공지글인 경우 글을 보임
                    if($arr['use_notice']=='Y'){
                        $rd_level = 1;
                        //로그인 되어있지 않은 경우 패스워드 폼을 보임
                    }else if($arr['mb_idx']==0 && !IS_MEMBER){
                        $rd_level = 3;
                        //그 외 아무 권한 없음
                    }else{
                        $rd_level = 0;
                    }
                }

            }
        }

        //글 조회 포인트 조정
        if($boardconf['read_point']<0){
            if(!IS_MEMBER){
                Func::err_back('포인트 설정으로 인해 비회원은 글을 조회할 수 없습니다.');
            }
            if(IS_MEMBER && !isset($view_sess) && $arr['mb_idx']!=$MB['idx']){
                if($MB['point']<(0-$boardconf['read_point'])){
                    Func::err_back('포인트가 부족하여 글을 조회할 수 없습니다.');
                }
                $point = 0 - $boardconf['read_point'];
                Func::set_mbpoint($MB['idx'],'out',$point,'게시판 글 조회 ('.$boardconf['title'].')');
            }

        }else if($boardconf['read_point']>0){
            Func::set_mbpoint($MB['idx'],'in',$boardconf['read_point'],'게시판 글 조회 ('.$boardconf['title'].')');
        }

        //조회수 증가
        if(!isset($view_sess)){
            $sql->query(
                $sql->scheme->view('update:hit'),
                array(
                    $req['read']
                )
            );
            $sess->set_sess('BOARD_VIEW_'.$req['read'],$req['read']);
        }

        //패스워드 입력폼 노출
        if($rd_level==3){

            self::$show_pwdform = 1;
        }

        //보기 권한이 없는 경우
        if($rd_level==0){

            switch($arr['use_secret']){
                case 'N' :
                Func::getlogin(SET_NOAUTH_MSG);
                break;
                case 'Y' :
                Func::err_back('접근 권한이 없습니다.');
                break;
            }

        }

        //view 노출
        if($rd_level==1){

            self::$show_pwdform = 0;

            if($arr['dregdate']){
                $is_dropbox_show = true;
                $is_article_show = false;
            }else{
                $is_dropbox_show = false;
                $is_article_show = true;
            }

            $is_file_show = array();
            for($i=1;$i<=2;$i++){
                if($arr['file'.$i]){
                    $is_file_show[$i] = true;
                }else{
                    $is_file_show[$i] = false;
                }
            }

            $is_img_show = array();
            for($i=1;$i<=2;$i++){
                if(print_imgfile($i,$arr)!=''){
                    $is_img_show[$i] = true;
                }else{
                    $is_img_show[$i] = false;
                }
            }

            if($boardconf['use_category']=='Y' && $arr['category'] && $arr['use_notice']=='N'){
                $is_category_show = true;
            }else{
                $is_category_show = false;
            }

            if($boardconf['use_comment']=='Y'){
                $is_comment_show = true;
            }else{
                $is_comment_show = false;
            }

            if($boardconf['use_likes']=='Y' && !$arr['dregdate']){
                $is_likes_show = true;
            }else{
                $is_likes_show = false;
            }

            if($boardconf['use_list']=='Y'){
                $is_ftlist_show = true;
            }else{
                $is_ftlist_show = false;
            }

            $arr['view'] = Func::number($arr['view']);
            $arr['date'] = Func::date($arr['regdate']);
            $arr['datetime'] = Func::datetime($arr['regdate']);
            $arr['likes_cnt'] = Func::number($arr['likes_cnt']);
            $arr['unlikes_cnt'] = Func::number($arr['unlikes_cnt']);

            $view = array();
            if(isset($arr)){
                foreach($arr as $key => $value){
                    $view[$key] = $value;
                }
            }else{
                $view = null;
            }

            $this->set('view',$view);
            $this->set('is_dropbox_show',$is_dropbox_show);
            $this->set('is_article_show',$is_article_show);
            $this->set('is_file_show',$is_file_show);
            $this->set('is_img_show',$is_img_show);
            $this->set('is_category_show',$is_category_show);
            $this->set('is_comment_show',$is_comment_show);
            $this->set('is_likes_show',$is_likes_show);
            $this->set('is_ftlist_show',$is_ftlist_show);
            $this->set('secret_ico',secret_ico($arr));
            $this->set('print_writer',print_writer($arr));
            $this->set('print_imgfile',print_imgfile($arr));
            $this->set('print_file_name',print_file_name($arr));
            $this->set('list_btn',list_btn($req['page'],$req['keyword'],$req['where'],$req['category']));
            $this->set('delete_btn',delete_btn($arr));
            $this->set('modify_btn',modify_btn($arr,$req['read'],$req['page'],$req['keyword'],$req['where'],$req['category']));
            $this->set('reply_btn',reply_btn($arr,$req['read'],$req['page'],$req['keyword'],$req['where'],$req['category']));

        }

        $this->set('mode',$req['mode']);
        $this->set('wrmode',$req['wrmode']);
        $this->set('board_id',$board_id);
        $this->set('category',$req['category']);
        $this->set('read',$req['read']);
        $this->set('page',$req['page']);
        $this->set('where',$req['where']);
        $this->set('keyword',$req['keyword']);
        $this->set('thisuri',Func::thisuri());
        $this->set('top_source',$boardconf['top_source']);
        $this->set('bottom_source',$boardconf['bottom_source']);
    }

}
