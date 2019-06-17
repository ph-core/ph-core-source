<?php
use Corelib\Method;
use Make\Library\Uploader;
use Make\Library\Imgresize;

include_once '../../lib/ph.core.php';

$uploader = new uploader();
$imgresize = new imgresize();

$req = Method::request('GET','CKEditorFuncNum');
$file = Method::request('FILE','upload');

$CKEditorFuncNum = $req['CKEditorFuncNum'];

//업로드된 파일 처리
$file_name = $uploader->replace_filename($file['upload']['name']);

//파일 저장
$uploader->file = $file['upload'];
$uploader->intdict = SET_IMGTYPE;
$return_src = PH_DOMAIN.'/data/'.PH_PLUGIN_CKEDITOR.'/thumb/'.$file_name;

if($uploader->chkfile('match')!==true){
    echo '
    {
        "error": {
            "message": "'.ERR_MSG_8.'"
        }
    }
    ';
    exit;
}

if($uploader->chkbyte(SET_MAX_UPLOAD)!==true){
    echo '
    {
        "error": {
            "message": "허용 파일 용량을 초과합니다."
        }
    }
    ';
    exit;
}

//업로드
$uploader->path = PH_PATH.'/data/'.PH_PLUGIN_CKEDITOR;
$uploader->chkpath();
$uploader->upload($file_name);

//썸네일 생성
$uploader->path = PH_PATH.'/data/'.PH_PLUGIN_CKEDITOR.'/thumb';
$uploader->chkpath();

$imgresize->set(
    array(
        'orgimg' => PH_PATH.'/data/'.PH_PLUGIN_CKEDITOR.'/'.$file_name,
        'newimg' => $uploader->path.'/'.$file_name,
        'width' => 1000
    )
);
$imgresize->make();

//return
echo '
{
    "filename" : "'.$file_name.'",
    "uploaded" : 1,
    "url" : "'.$return_src.'",
    "width" : "auto",
    "height" : "auto",
    "massage" : "성공적으로 업로드 되었습니다."
}
';
?>
