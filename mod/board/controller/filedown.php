<?php
namespace Module\Board;

use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Uploader;

class Filedown extends \Controller\Make_Controller{

    public function _init(){
        $this->_func();
        $this->_make();
    }

    public function _func(){
        //IE인지 확인
        function is_ie(){
            if(!isset($_SERVER['HTTP_USER_AGENT'])){
                return false;
            }
            if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')!==false){
                return true;
            } //IE8
            if(strpos($_SERVER['HTTP_USER_AGENT'],'Windows NT')!==false){
                return true;
            } //IE11
            if(!isset($_SERVER['HTTP_USER_AGENT'])){
                return false;
            }
            return false;
        }
    }

    public function _make(){
        global $board_id;

        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        $req = Method::request('GET','board_id,file');

        $board_id = $req['board_id'];

        //게시글의 첨부파일 정보 불러옴
        $sql->query(
            $sql->scheme->view('select:files'),
            array(
                $req['file']
            )
        );

        //첨부파일이 확인되지 않는 경우
        if($sql->getcount()<1){
            Func::err('첨부파일이 확인되지 않습니다.');
        }

        //첨부파일 정보
        $file = urldecode($req['file']);
        $fileinfo = array();
        $fileinfo['path'] = MOD_BOARD_DATA_PATH.'/'.$board_id.'/'.$req['file'];
        $fileinfo['size'] = filesize($fileinfo['path']);
        $fileinfo['parts'] = pathinfo($fileinfo['path']);
        $fileinfo['name'] = $fileinfo['parts']['basename'];

        $qry_file = array();
        $qry_file_cnt = array();
        for($i=1;$i<=2;$i++){
            $isfile = $sql->fetch('file'.$i);
            if($isfile==$file){
                $qry_file_cnt[$i] = 1;
            }else{
                $qry_file_cnt[$i] = 0;
            }
        }

        //파일 다운로드 횟수 증가
        $sql->query(
            $sql->scheme->view('update:file_hit'),
            array(
                $qry_file_cnt[1],$qry_file_cnt[2],$file
            )
        );

        //IE를 위한 처리
        if(is_ie()){
            $fileinfo['name'] = iconv('UTF-8','EUC-KR',$fileinfo['name']);
        }

        //파일 다운로드 스트림
        Header('Content-Type:application/octet-stream');
        Header('Content-Disposition:attachment; filename='.$fileinfo['name']);
        Header('Content-Transfer-Encoding:binary');
        Header('Content-Length:'.(string)$fileinfo['size']);
        Header('Cache-Control:Cache,must-revalidate');
        Header('Pragma:No-Cache');
        Header('Expires:0');
        ob_clean();
        flush();
        readfile($fileinfo['path']);
    }

}
