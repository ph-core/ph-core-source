<?php
namespace Make\Database;

class Scheme extends \Make\Database\Pdosql{

    public function layout($type){

        if($type=="select:cat_caidx"){
            return "
                SELECT *
                FROM {$this->table("sitemap")}
                WHERE idx=:col1
            ";
        }

        if($type=="select:status"){
            return "
                SELECT *
                FROM {$this->table("sitemap")}
                WHERE caidx=:col1
            ";
        }

        if($type=="select:sitemap_1d"){
          return "
            SELECT *
            FROM {$this->table("sitemap")}
            WHERE CHAR_LENGTH(caidx)=4 AND visible='Y'
            ORDER BY caidx ASC
          ";
        }

        if($type=="select:sitemap_2d"){
          return "
            SELECT *
            FROM {$this->table("sitemap")}
            WHERE SUBSTR(caidx,1,4)=:col1 AND CHAR_LENGTH(caidx)=8 AND visible='Y'
            ORDER BY caidx ASC
          ";
        }

        if($type=="select:sitemap_3d"){
          return "
            SELECT *
            FROM {$this->table("sitemap")}
            WHERE SUBSTR(caidx,1,8)=:col1 AND CHAR_LENGTH(caidx)=12 AND visible='Y'
            ORDER BY caidx ASC
          ";
        }

    }

    public function manage($type){

        if($type=="insert:addfeeds"){
            return "
                INSERT into {$this->table("mng_feeds")}
                (msg_from,memo,href,regdate)
                VALUES
                (:col1,:col2,:col3,now())
            ";
        }

    }

    public function globals($type){

        if($type=="gb:siteconfig"){
            return "
                SELECT *
                FROM {$this->table("siteconfig")}
                LIMIT 1
            ";
        }

        if($type=="gb:memberinfo"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_idx=:col1
            ";
        }

    }

    public function core($type){

        if($type=="select:session"){
            return "
                SELECT *
                FROM {$this->table("session")}
                WHERE sesskey=:col1 AND expiry>
            ".time();
        }

        if($type=="insert:session"){
            return "
                INSERT INTO {$this->table("session")}
                (sesskey,expiry,value,mb_idx,ip,regdate)
                VALUES
                (:col1,:col2,'','',:col3,now())
            ";
        }

        if($type=="update:session"){
            return "
                UPDATE {$this->table("session")}
                SET expiry=:col1,value=:col2,regdate=now()
                WHERE sesskey=:col3 AND expiry>
            ".time();
        }

        if($type=="update:session2"){
            return "
                UPDATE {$this->table("session")}
                SET expiry=:col1,value=:col2,regdate=now(),mb_idx=:col3
                WHERE sesskey=:col4 AND expiry>
            ".time();
        }

        if($type=="select:visitcount"){
            return "
                SELECT *
                FROM {$this->table("visitcount")}
                WHERE ip=:col1 AND regdate>=DATE_SUB(now(),interval 1 hour)
            ";
        }

        if($type=="insert:visitcount"){
            return "
                INSERT into {$this->table("visitcount")}
                (mb_idx,mb_id,ip,device,browser,regdate)
                VALUES
                (:col1,:col2,:col3,:col4,:col5,now())
            ";
        }

        if($type=="update:visitcount"){
            return "
                UPDATE {$this->table("visitcount")}
                SET mb_idx=:col1,mb_id=:col2,device=:col3,browser=:col4
                WHERE ip=:col5
                ORDER BY regdate DESC
                LIMIT 1
            ";
        }

        if($type=="delete:session"){
            return "
                DELETE
                FROM {$this->table("session")}
                WHERE sesskey=:col1
            ";
        }

        if($type=="gc:session"){
            return "
                DELETE
                FROM {$this->table("session")}
                WHERE expiry<
            ".time();
        }

        if($type=="select:banner"){
            return "
                SELECT *
                FROM {$this->table("banner")}
                WHERE bn_key=:col1
                ORDER BY zindex ASC
            ";
        }

        if($type=="select:blocked"){
            return "
                SELECT *
                FROM {$this->table("blockmb")}
                WHERE (ip=:col1 OR ip=:col2 OR ip=:col3 OR ip=:col4) OR (mb_idx=:col5 AND mb_id=:col6)
            ";
        }

        if($type=="select:mailtpl"){
            return "
                SELECT *
                FROM {$this->table("mailtpl")}
                WHERE type=:col1
            ";
        }

        if($type=="select:popup"){
            return "
                SELECT *
                FROM {$this->table("popup")}
                WHERE show_from<now() AND show_to>now()
            ";
        }

    }

    public function member($type){

        if($type=="select:signin"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_id=:col1 AND mb_dregdate IS NULL AND (mb_pwd=old_password(:col2) OR mb_pwd=password(:col2))
            ";
        }

        if($type=="select:id_inspt"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_id=:col1 AND mb_dregdate IS NULL
            ";
        }

        if($type=="select:email_inspt"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_email=:col1 AND mb_dregdate IS NULL
            ";
        }

        if($type=="insert:signup"){
            return "
                INSERT INTO {$this->table("member")}
                (mb_id,mb_email,mb_pwd,mb_name,mb_gender,mb_phone,mb_telephone,mb_email_chk,mb_regdate,mb_1,mb_2,mb_3,mb_4,mb_5,mb_6,mb_7,mb_8,mb_9,mb_10,mb_exp)
                VALUES
                (:col1,:col2,password(:col3),:col4,:col5,:col6,:col7,:col8,now(),:col9,:col10,:col11,:col12,:col13,:col14,:col15,:col16,:col17,:col18,:col19)
            ";
        }

        if($type=="insert:mbchk"){
            return "
                INSERT INTO {$this->table("mbchk")}
                (mb_idx,chk_code,chk_chk,chk_regdate)
                VALUES
                (:col1,:col2,'N',now())
            ";
        }

        if($type=="select:nochked_mb"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_idx=:col1 AND mb_email_chk='N' AND mb_dregdate IS NULL
            ";
        }

        if($type=="select:mb_idx"){
            return "
                SELECT mb_idx
                FROM {$this->table("member")}
                WHERE mb_id=:col1 AND mb_pwd=password(:col2) AND mb_dregdate IS NULL
            ";
        }

        if($type=="update:mblately"){
            return "
                UPDATE {$this->table("member")}
                SET mb_lately_ip=:col1,mb_lately=now()
                WHERE mb_idx=:col2
            ";
        }

        if($type=="select:mbinfo"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_idx=:col1 AND mb_dregdate IS NULL
            ";
        }

        if($type=="update:mbinfo"){
            return "
                UPDATE {$this->table("member")}
                SET mb_pwd=password(:col1),mb_name=:col2,mb_gender=:col3,mb_phone=:col4,mb_telephone=:col5,mb_email_chg=:col6
                WHERE mb_idx=:col7 AND mb_dregdate IS NULL
            ";
        }

        if($type=="update:mbinfo2"){
            return "
                UPDATE {$this->table("member")}
                SET mb_pwd=:col1,mb_name=:col2,mb_gender=:col3,mb_phone=:col4,mb_telephone=:col5,mb_email_chg=:col6
                WHERE mb_idx=:col7 AND mb_dregdate IS NULL
            ";
        }

        if($type=="update:mbleave"){
            return "
                UPDATE {$this->table("member")}
                SET mb_dregdate=now()
                WHERE mb_idx=:col1 AND mb_dregdate IS NULL
            ";
        }

        if($type=="select:emailvalid"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_email=:col1 AND mb_email!=:col2 AND mb_dregdate IS NULL
            ";
        }

        if($type=="select:mbpointlist"){
            return "
                SELECT *
                FROM {$this->table("mbpoint")}
                WHERE mb_idx=:col1
                ORDER BY regdate DESC
            ";
        }

        if($type=="select:mbpoint"){
            return "
                SELECT mb_point
                FROM {$this->table("member")}
                WHERE mb_idx=:col1
                LIMIT 1
            ";
        }

        if($type=="insert:pointin"){
            return "
                INSERT INTO {$this->table("mbpoint")}
                (mb_idx,p_in,memo,regdate)
                VALUES
                (:col1,:col2,:col3,now())
            ";
        }

        if($type=="insert:pointout"){
            return "
                INSERT INTO {$this->table("mbpoint")}
                (mb_idx,p_out,memo,regdate)
                VALUES
                (:col1,:col2,:col3,now())
            ";
        }

        if($type=="update:mbpoint"){
            return "
                UPDATE {$this->table("member")}
                SET mb_point=:col1
                WHERE mb_idx=:col2
            ";
        }

        if($type=="insert:mbchk_chg"){
            return "
                INSERT INTO {$this->table("mbchk")}
                (mb_idx,chk_code,chk_chk,chk_mode,chk_regdate)
                VALUES
                (:col1,:col2,'N','chg',now())
            ";
        }

        if($type=="select:mbchk"){
            return "
                SELECT *
                FROM {$this->table("mbchk")}
                WHERE chk_code=:col1
            ";
        }

        if($type=="select:mbchk2"){
            return "
                SELECT *
                FROM {$this->table("mbchk")}
                WHERE mb_idx=:col1
                ORDER BY chk_regdate DESC
                LIMIT 1
            ";
        }

        if($type=="update:mbchk"){
            return "
                UPDATE {$this->table("member")}
                SET mb_email_chk='Y'
                WHERE mb_idx=:col1
            ";
        }

        if($type=="update:mbchk2"){
            return "
                UPDATE {$this->table("member")}
                SET mb_email=mb_email_chg,mb_email_chg=''
                WHERE mb_idx=:col1
            ";
        }

        if($type=="update:mbchk3"){
            return "
                UPDATE {$this->table("mbchk")}
                SET chk_chk='Y'
                WHERE chk_code=:col1
            ";
        }

        if($type=="select:forgot"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_email=:col1 AND mb_dregdate IS NULL
            ";
        }

        if($type=="update:pwd"){
            return "
                UPDATE {$this->table("member")}
                SET mb_pwd=password(:col1)
                WHERE mb_id=:col2 AND mb_dregdate IS NULL
            ";
        }

    }

}
