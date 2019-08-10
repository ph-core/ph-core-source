<?php
use Corelib\Func;
use Make\Database\Pdosql;

class Banner_fetch{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        global $CONF,$FETCH_CONF;

        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        //배너 정보
        $sql->query(
            $sql->scheme->core('select:banner'),
            array(
                $FETCH_CONF['key']
            )
        );

        if($sql->getcount()<1){
            Func::err_print('Banner Key 가 존재하지 않습니다. : '.$FETCH_CONF['key']);
        }

        //배너 html 출력
        $bn_html = '
            <ul>
        ';

        do{
            $bn_arr = $sql->fetchs();

            $bn_html .= '
                <li alt="'.$bn_arr['title'].'" title="'.$bn_arr['title'].'">
            ';
            if($bn_arr['link']!=''){
                $bn_html .= '
                    <a href="'.PH_DOMAIN.PH_MANAGE_DIR.'/bannerhit.php?idx='.$bn_arr['idx'].'&key='.$bn_arr['bn_key'].'" target="'.$bn_arr['link_target'].'" class="link">
                ';
            }
            if(Func::chkdevice()=='pc' || $CONF['use_mobile']=='N'){
                if($bn_arr['pc_img']){
                    $bn_html .= '
                        <img src="'.PH_DATA_DIR.'/manage/'.$bn_arr['pc_img'].'" />
                    ';
                }else{
                    $bn_html .= '
                        <img src="'.PH_DIR.'/layout/images/blank-banner.jpg" width="100%" />
                    ';
                }
            }else{
                if($bn_arr['mo_img']){
                    $bn_html .= '
                        <img src="'.PH_DATA_DIR.'/manage/'.$bn_arr['mo_img'].'" />
                    ';
                }else{
                    $bn_html .= '
                        <img src="'.PH_DIR.'/layout/images/blank-banner.jpg" width="100%" />
                    ';
                }
            }
            if($bn_arr['link']!=''){
                $bn_html .= '
                    </a>
                ';
            }
            $bn_html .= '
                </li>
            ';

        }while($sql->nextRec());

        $bn_html .= '
            </ul>
        ';

        if($sql->getcount()>0){
            echo $bn_html;
        }
    }

}
