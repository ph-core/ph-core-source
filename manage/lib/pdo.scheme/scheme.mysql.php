<?php
namespace Manage;

class Scheme extends \Make\Database\Pdosql{

    public function main($type){

        if($type=="select:new_member"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_adm!='Y' AND mb_dregdate IS NULL
                ORDER BY mb_regdate DESC
                LIMIT 5
            ";
        }

        if($type=="select:visit_member"){
            return "
                SELECT visit.*,IFNULL(member.mb_level,10) mb_level
                FROM {$this->table("visitcount")} visit
                LEFT OUTER JOIN {$this->table("member")} member
                ON visit.mb_idx=member.mb_idx
                ORDER BY regdate DESC
                LIMIT 5
            ";
        }

        if($type=="select:sess_member"){
            return "
                SELECT sess.*,member.*,IFNULL(member.mb_level,10) mb_level
                FROM {$this->table("session")} sess
                LEFT OUTER JOIN
                {$this->table("member")} member
                ON sess.mb_idx=member.mb_idx
                WHERE regdate>=DATE_SUB(now(),interval 10 minute)
                ORDER BY regdate DESC
            ";
        }

        if($type=="update:feeds_read_all"){
            return "
                UPDATE {$this->table("mng_feeds")}
                SET chked='Y'
                WHERE chked='N'
            ";
        }

        if($type=="update:feeds_read"){
            return "
                UPDATE {$this->table("mng_feeds")}
                SET chked='Y'
                WHERE idx=:col1 AND chked='N'
            ";
        }

        if($type=="select:feeds"){
            return "
                SELECT *,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mng_feeds")}
                    WHERE chked='N'
                ) AS total
                FROM {$this->table("mng_feeds")}
                ORDER BY regdate DESC
            ";
        }

    }

    public function siteinfo($type){

        if($type=="select:siteinfo"){
            return "
                SELECT *
                FROM {$this->table("siteconfig")}
                LIMIT 1
            ";
        }

        if($type=="update:siteinfo"){
            return "
                UPDATE
                {$this->table("siteconfig")}
                SET
                st_title=:col1,st_domain=:col2,st_description=:col3,st_use_mobile=:col4,st_use_emailchk=:col5,st_use_recaptcha=:col6,st_recaptcha_key1=:col7,st_recaptcha_key2=:col8,st_use_sns_ka=:col9,st_sns_ka_key1=:col10,st_sns_ka_key2=:col11,st_use_sns_nv=:col12,st_sns_nv_key1=:col13,st_sns_nv_key2=:col14,st_email=:col15,st_tel=:col16,st_favicon=:col17,st_logo=:col18,st_mb_division=:col19,st_use_smtp=:col20,st_smtp_server=:col21,st_smtp_port=:col22,st_smtp_id=:col23,st_smtp_pwd=:col24,st_privacy=:col25,st_policy=:col26,st_1=:col27,st_2=:col28,st_3=:col29,st_4=:col30,st_5=:col31,st_6=:col32,st_7=:col33,st_8=:col34,st_9=:col35,st_10=:col36,st_exp=:col37
            ";
        }

    }

    public function metaconf($type){

        if($type=="update:metaconf"){
            return "
                UPDATE
                {$this->table("siteconfig")}
                SET
                st_og_type=:col1,st_og_title=:col2,st_og_description=:col3,st_og_url=:col4,st_og_image=:col5,st_naver_verific=:col6,st_google_verific=:col7,st_script=:col8,st_meta=:col9
            ";
        }

    }

    public function sitemap($type){
        global $where;

        if($type=="select:list_1d"){
            return "
                SELECT *
                FROM {$this->table("sitemap")}
                WHERE CHAR_LENGTH(caidx)=4
                ORDER BY caidx ASC
            ";
        }

        if($type=="select:list_2d"){
            return "
                SELECT *
                FROM {$this->table("sitemap")}
                WHERE SUBSTR(caidx,1,4)=:col1 AND CHAR_LENGTH(caidx)=8
                ORDER BY caidx ASC
            ";
        }

        if($type=="select:list_3d"){
            return "
                SELECT *
                FROM {$this->table("sitemap")}
                WHERE SUBSTR(caidx,1,8)=:col1 AND CHAR_LENGTH(caidx)=12
                ORDER BY caidx ASC
            ";
        }

        if($type=="select:sitemap"){
            return "
                SELECT *
                FROM {$this->table("sitemap")}
                WHERE idx=:col1
            ";
        }

        if($type=="select:sitemap_rct"){
            return "
                SELECT *
                FROM {$this->table("sitemap")}
                ORDER BY idx DESC
                LIMIT 1
            ";
        }

        if($type=="select:children_count"){
            return "
                SELECT COUNT(*) count
                FROM {$this->table("sitemap")}
                WHERE caidx LIKE :col1
            ";
        }

        if($type=="update:sitemap"){
            return "
                UPDATE {$this->table("sitemap")}
                SET
                title=:col1,href=:col2,visible=:col3
                WHERE idx=:col4
            ";
        }

        if($type=="update:sitemap2"){
            return "
                UPDATE {$this->table("sitemap")}
                SET
                caidx=:col1,children=:col2
                WHERE idx=:col3
            ";
        }

        if($type=="update:parent"){
            return "
                UPDATE {$this->table("sitemap")}
                SET
                children=:col1
                WHERE caidx=:col2
            ";
        }

        if($type=="insert:sitemap"){
            return "
                INSERT INTO
                {$this->table("sitemap")}
                (idx,caidx,title,children)
                VALUES
                (:col1,:col2,:col3,:col4)
            ";
        }

        if($type=="delete:sitemap"){
            return "
                DELETE
                FROM {$this->table("sitemap")}
                WHERE $where
            ";
        }

    }

    public function theme($type){

        if($type=="update:theme"){
            return "
                UPDATE
                {$this->table("siteconfig")}
                SET
                st_theme=:col1
            ";
        }

    }

    public function member($type){

        if($type=="sort:mblist"){
            return "
                SELECT
                (
                    SELECT COUNT(*)
                    FROM {$this->table("member")}
                    WHERE mb_adm!='Y' AND mb_dregdate IS NULL
                ) mb_total,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("member")}
                    WHERE mb_email_chk='Y' AND mb_adm!='Y' AND mb_dregdate IS NULL
                ) emchk_total
            ";
        }

        if($type=="sort:mbleave"){
            return "
                SELECT
                (
                    SELECT count(*)
                    FROM {$this->table("member")}
                    WHERE mb_adm!='Y' AND mb_dregdate IS NOT NULL
                ) mb_total
            ";
        }

        if($type=="sort:mbstat"){
            return "
                SELECT
                (
                    SELECT COUNT(*)
                    FROM {$this->table("session")}
                    WHERE regdate>=DATE_SUB(now(),interval 10 minute)
                ) stat_total
            ";
        }

        if($type=="sort:mbvisit"){
            return "
                SELECT
                (
                    SELECT COUNT(*)
                    FROM {$this->table("visitcount")}
                ) visit_total,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("visitcount")}
                    WHERE DATE_FORMAT(regdate,'%Y-%m-%d') BETWEEN :col1 AND :col2
                ) device_total,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("visitcount")}
                    WHERE DATE_FORMAT(regdate,'%Y-%m-%d') BETWEEN :col1 AND :col2 AND device='pc'
                ) device_pc,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("visitcount")}
                    WHERE DATE_FORMAT(regdate,'%Y-%m-%d') BETWEEN :col1 AND :col2 AND mb_idx!=0
                ) member_total
            ";
        }

        if($type=="sort:mbpoint"){
            return "
                SELECT
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mbpoint")}
                ) act_total,
                (
                    SELECT SUM(p_in)
                    FROM {$this->table("mbpoint")}
                    WHERE p_in>0
                ) in_total,
                (
                    SELECT SUM(p_out)
                    FROM {$this->table("mbpoint")}
                    WHERE p_out>0
                ) out_total
            ";
        }

        if($type=="select:mblist"){
            global $sortby,$searchby,$orderby;
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_adm!='Y' AND mb_dregdate IS NULL $sortby $searchby
                ORDER BY $orderby
            ";
        }

        if($type=="select:mbleave"){
            global $sortby,$searchby,$orderby;
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_adm!='Y' AND mb_dregdate IS NOT NULL $sortby $searchby
                ORDER BY $orderby
            ";
        }

        if($type=="select:mbvisit"){
            global $sortby,$searchby,$orderby;
            return "
                SELECT visit.*,IFNULL(member.mb_level,10) mb_level
                FROM {$this->table("visitcount")} visit
                LEFT OUTER JOIN {$this->table("member")} member
                ON visit.mb_idx=member.mb_idx
                WHERE DATE_FORMAT(visit.regdate,'%Y.%m.%d') BETWEEN date(:col1) AND date(:col2) $sortby $searchby
                ORDER BY $orderby
            ";
        }

        if($type=="select:mbstat"){
            global $sortby,$searchby,$orderby;
            return "
                SELECT sess.*,member.*,IFNULL(member.mb_level,10) mb_level
                FROM {$this->table("session")} sess
                LEFT OUTER JOIN
                {$this->table("member")} member
                ON sess.mb_idx=member.mb_idx
                WHERE regdate>=DATE_SUB(now(),interval 10 minute) $sortby $searchby
                ORDER BY $orderby
            ";
        }

        if($type=="select:mbpoint"){
            global $sortby,$searchby,$orderby;
            return "
                SELECT mbpoint.*,member.*
                FROM {$this->table("mbpoint")} mbpoint
                LEFT OUTER JOIN
                {$this->table("member")} member
                ON mbpoint.mb_idx=member.mb_idx
                WHERE 1 $sortby $searchby
                ORDER BY $orderby
            ";
        }

        if($type=="select:member"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_adm!='Y' AND mb_dregdate IS NULL AND mb_idx=:col1
                LIMIT 1
            ";
        }

        if($type=="select:member2"){
            return "
                SELECT mb_idx
                FROM {$this->table("member")}
                WHERE mb_id=:col1 AND mb_pwd=password(:col2) AND mb_dregdate IS NULL
            ";
        }

        if($type=="select:email_chk"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_dregdate IS NULL AND mb_email=:col1 AND mb_idx!=:col2
            ";
        }

        if($type=="select:email_chk2"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_email=:col1 AND mb_dregdate IS NULL
            ";
        }

        if($type=="select:id_chk"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_id=:col1 AND mb_dregdate IS NULL
            ";
        }

        if($type=="update:member"){
            return "
                UPDATE {$this->table("member")}
                SET mb_pwd=:col1,mb_name=:col2,mb_gender=:col3,mb_phone=:col4,mb_telephone=:col5,mb_point=:col6,mb_level=:col7,mb_email=:col8,mb_email_chk=:col9,mb_1=:col10,mb_2=:col11,mb_3=:col12,mb_4=:col13,mb_5=:col14,mb_6=:col15,mb_7=:col16,mb_8=:col17,mb_9=:col18,mb_10=:col19,mb_exp=:col20
                WHERE mb_adm!='Y' AND mb_dregdate IS NULL AND mb_idx=:col21
            ";
        }

        if($type=="update:member2"){
            return "
                UPDATE {$this->table("member")}
                SET mb_pwd=password(:col1),mb_name=:col2,mb_gender=:col3,mb_phone=:col4,mb_telephone=:col5,mb_point=:col6,mb_level=:col7,mb_email=:col8,mb_email_chk=:col9,mb_1=:col10,mb_2=:col11,mb_3=:col12,mb_4=:col13,mb_5=:col14,mb_6=:col15,mb_7=:col16,mb_8=:col17,mb_9=:col18,mb_10=:col19,mb_exp=:col20
                WHERE mb_adm!='Y' AND mb_dregdate IS NULL AND mb_idx=:col21
            ";
        }

        if($type=="update:delete"){
            return "
                UPDATE {$this->table("member")}
                SET mb_dregdate=now()
                WHERE mb_dregdate IS NULL AND mb_idx=:col1
            ";
        }

        if($type=="insert:member"){
            return "
                INSERT INTO {$this->table("member")}
                (mb_id,mb_email,mb_pwd,mb_name,mb_level,mb_gender,mb_phone,mb_telephone,mb_email_chk,mb_regdate,mb_1,mb_2,mb_3,mb_4,mb_5,mb_6,mb_7,mb_8,mb_9,mb_10,mb_exp)
                VALUES
                (:col1,:col2,password(:col3),:col4,:col5,:col6,:col7,:col8,:col9,now(),:col10,:col11,:col12,:col13,:col14,:col15,:col16,:col17,:col18,:col19,:col20)
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

        if($type=="select:mbidxs"){
            global $id_qry;
            return "
                SELECT mb_idx
                FROM {$this->table("member")}
                WHERE mb_dregdate IS NULL AND ($id_qry)
            ";
        }

    }

    public function popup($type){


      if($type=="sort:poplist"){
          return "
              SELECT
              (
                  SELECT COUNT(*)
                  FROM {$this->table("popup")}
              ) pop_total,
              (
                  SELECT COUNT(*)
                  FROM {$this->table("popup")}
                  WHERE show_from<now() AND show_to>now()
              ) use_pop,
              (
                  SELECT COUNT(*)
                  FROM {$this->table("popup")}
                  WHERE (show_from>now() OR show_to<now())
              ) notuse_pop
          ";
      }

      if($type=="select:poplist"){
          global $sortby,$searchby,$orderby;
          return "
              SELECT *
              FROM {$this->table("popup")}
              WHERE 1 $sortby $searchby
              ORDER BY $orderby
          ";
      }

      if($type=="select:popup"){
          return "
              SELECT *
              FROM {$this->table("popup")}
              WHERE idx=:col1
              LIMIT 1
          ";
      }

      if($type=="select:popup2"){
          return "
              SELECT *
              FROM {$this->table("popup")}
              WHERE id=:col1
              LIMIT 1
          ";
      }

      if($type=="update:popup"){
          return "
              UPDATE {$this->table("popup")}
              SET title=:col1,link=:col2,link_target=:col3,width=:col4,height=:col5,pos_top=:col6,pos_left=:col7,level_from=:col8,level_to=:col9,show_from=:col10,show_to=:col11,html=:col12,mo_html=:col13
              WHERE idx=:col14
          ";
      }

      if($type=="delete:popup"){
          return "
              DELETE
              FROM {$this->table("popup")}
              WHERE idx=:col1
          ";
      }

      if($type=="insert:popup"){
          return "
              INSERT INTO {$this->table("popup")}
              (id,title,link,link_target,width,height,pos_left,pos_top,level_from,level_to,show_from,show_to,html,mo_html,regdate)
              VALUES
              (:col1,:col2,:col3,:col4,:col5,:col6,:col7,:col8,:col9,:col10,:col11,:col12,:col13,:col14,now())
          ";
      }

  }

  public function banner($type){

      if($type=="sort:bnlist"){
          return "
              SELECT
              (
                  SELECT COUNT(*)
                  FROM {$this->table("popup")}
              ) bn_total
          ";
      }

      if($type=="select:bnlist"){
          global $sortby,$searchby,$orderby;
          return "
              SELECT *
              FROM {$this->table("banner")}
              WHERE 1 $sortby $searchby
              ORDER BY $orderby
          ";
      }

      if($type=="select:banner"){
          return "
              SELECT *
              FROM {$this->table("banner")}
              WHERE idx=binary(:col1)
              LIMIT 1
          ";
      }

      if($type=="select:banner2"){
          return "
              SELECT *
              FROM {$this->table("banner")}
              WHERE bn_key=:col1
              ORDER BY regdate DESC
          ";
      }

      if($type=="update:banner"){
          return "
              UPDATE {$this->table("banner")}
              SET bn_key=:col1,title=:col2,link=:col3,link_target=:col4,pc_img=:col5,mo_img=:col6,zindex=:col7
              WHERE idx=:col8
          ";
      }

      if($type=="delete:banner"){
          return "
              DELETE
              FROM {$this->table("banner")}
              WHERE idx=:col1
          ";
      }

      if($type=="insert:banner"){
          return "
              INSERT INTO {$this->table("banner")}
              (bn_key,title,link,link_target,pc_img,mo_img,zindex,regdate)
              VALUES
              (:col1,:col2,:col3,:col4,:col5,:col6,:col7,now())
          ";
      }

  }

  public function mailtpl($type){

      if($type=="sort:mailtpl"){
          return "
              SELECT
              (
                  SELECT COUNT(*)
                  FROM {$this->table("mailtpl")}
              ) total
          ";
      }

      if($type=="select:mailtpls"){
          global $sortby,$searchby,$orderby;
          return "
              SELECT *
              FROM {$this->table("mailtpl")}
              WHERE 1 $sortby $searchby
              ORDER BY $orderby
          ";
      }

      if($type=="select:mailtpl"){
          return "
              SELECT *
              FROM {$this->table("mailtpl")}
              WHERE idx=:col1
              LIMIT 1
          ";
      }

      if($type=="select:mailtpl2"){
          return "
              SELECT *
              FROM {$this->table("mailtpl")}
              WHERE type=:col1
              ORDER BY regdate DESC
          ";
      }

      if($type=="select:mailtpl3"){
          return "
              SELECT *
              FROM {$this->table("mailtpl")}
              WHERE system='N' OR type='default'
              ORDER BY type ASC
          ";
      }

      if($type=="update:mailtpl"){
          return "
              UPDATE {$this->table("mailtpl")}
              SET html=:col1
              WHERE idx=:col2
          ";
      }

      if($type=="update:mailtpl2"){
          return "
              UPDATE {$this->table("mailtpl")}
              SET title=:col1,html=:col2
              WHERE idx=:col3
          ";
      }

      if($type=="delete:mailtpl"){
          return "
              DELETE
              FROM {$this->table("mailtpl")}
              WHERE idx=:col1
          ";
      }

      if($type=="insert:mailtpl"){
          return "
              INSERT INTO {$this->table("mailtpl")}
              (type,title,html,regdate)
              VALUES
              (:col1,:col2,:col3,now())
          ";
      }

  }

  public function sendmail($type){

      if($type=="sort:mailhis"){
          return "
              SELECT
              (
                  SELECT COUNT(*)
                  FROM {$this->table("sentmail")}
              ) total,
              (
                  SELECT COUNT(*)
                  FROM {$this->table("sentmail")}
                  WHERE to_mb IS NOT NULL AND to_mb!=''
              ) to_mb_total,
              (
                  SELECT COUNT(*)
                  FROM {$this->table("sentmail")}
                  WHERE to_mb IS NULL OR to_mb=''
              ) level_from_total
          ";
      }

      if($type=="select:to_mb"){
          return "
              SELECT *
              FROM {$this->table("member")}
              WHERE mb_id=:col1 AND mb_dregdate IS NULL
          ";
      }

      if($type=="select:to_level"){
          return "
              SELECT *
              FROM {$this->table("member")}
              WHERE mb_level>=:col1 AND mb_level<=:col2 AND mb_dregdate IS NULL
              ORDER BY mb_idx ASC
          ";
      }

      if($type=="insert:sendmail"){
          return "
              INSERT INTO {$this->table("sentmail")}
              (template,to_mb,level_from,level_to,subject,html,regdate)
              VALUES
              (:col1,:col2,:col3,:col4,:col5,:col6,now())
          ";
      }

      if($type=="select:sendmail"){
          return "
              SELECT idx
              FROM {$this->table("sentmail")}
              WHERE subject=:col1
              ORDER BY regdate DESC
              LIMIT 1
          ";
      }

      if($type=="select:sendmails"){
          global $sortby,$searchby,$orderby;
          return "
              SELECT *
              FROM {$this->table("sentmail")}
              WHERE 1 $sortby $searchby
              ORDER BY $orderby
          ";
      }

      if($type=="select:sentmail"){
          return "
              SELECT *
              FROM {$this->table("sentmail")}
              WHERE idx=:col1
              LIMIT 1
          ";
      }

  }

  public function blocked($type){

      if($type=="sort:blockip"){
          return "
              SELECT
              (
                  SELECT COUNT(*)
                  FROM {$this->table("blockmb")}
                  WHERE ip IS NOT NULL AND ip!=''
              ) total
          ";
          }

          if($type=="sort:blockmb"){
              return "
                  SELECT
                  (
                      SELECT COUNT(*)
                      FROM {$this->table("blockmb")}
                      WHERE mb_id IS NOT NULL AND mb_id!=''
                  ) total
              ";
          }

          if($type=="select:blockrec"){
              return "
                  SELECT *
                  FROM {$this->table("blockmb")}
                  WHERE idx=:col1
              ";
          }

          if($type=="select:blockips"){
              global $sortby,$searchby,$orderby;
              return "
                  SELECT *
                  FROM {$this->table("blockmb")}
                  WHERE ip IS NOT NULL AND ip!='' $sortby $searchby
                  ORDER BY $orderby
              ";
          }

          if($type=="select:blockmbs"){
              global $sortby,$searchby,$orderby;
              return "
                  SELECT *
                  FROM {$this->table("blockmb")}
                  WHERE mb_id IS NOT NULL AND mb_id!='' $sortby $searchby
                  ORDER BY $orderby
              ";
          }

          if($type=="select:blockip"){
              return "
                  SELECT *
                  FROM {$this->table("blockmb")}
                  WHERE ip=:col1
              ";
          }

          if($type=="select:chk_mb"){
              return "
                  SELECT *
                  FROM {$this->table("blockmb")}
                  WHERE mb_idx=:col1 AND mb_id=:col2
              ";
          }

          if($type=="insert:blockip"){
              return "
                  INSERT INTO
                  {$this->table("blockmb")}
                  (ip,memo,regdate)
                  VALUES
                  (:col1,:col2,now())
              ";
          }

          if($type=="insert:blockmb"){
              return "
                  INSERT INTO
                  {$this->table("blockmb")}
                  (mb_idx,mb_id,memo,regdate)
                  VALUES
                  (:col1,:col2,:col3,now())
              ";
          }

          if($type=="delete:blockrec"){
              return "
                  DELETE FROM {$this->table("blockmb")}
                  WHERE idx=:col1
              ";
          }

      }

      public function bannerhit($type){

          if($type=="select:banner"){
              return "
                  SELECT link
                  FROM {$this->table("banner")}
                  WHERE idx=:col1 AND bn_key=:col2
              ";
          }

          if($type=="update:hit"){
              return "
                  UPDATE
                  {$this->table("banner")}
                  SET hit=hit+1
                  WHERE idx=:col1 AND bn_key=:col2
              ";
          }

      }

      public function adminfo($type){

          if($type=="select:id_chk"){
              return "
                  SELECT *
                  FROM {$this->table("member")}
                  WHERE mb_id=:col1 AND mb_dregdate IS NULL AND mb_adm!='Y'
              ";
          }

          if($type=="select:email_chk"){
              return "
                  SELECT *
                  FROM {$this->table("member")}
                  WHERE mb_dregdate IS NULL AND mb_email=:col1 AND mb_adm!='Y'
              ";
          }

          if($type=="update:adminfo"){
              return "
                UPDATE {$this->table("member")}
                  SET mb_id=:col1,mb_name=:col2,mb_pwd=:col3,mb_email=:col4
                  WHERE mb_adm='Y' AND mb_idx=:col5
              ";
          }

          if($type=="update:adminfo2"){
              return "
                  UPDATE {$this->table("member")}
                  SET mb_id=:col1,mb_name=:col2,mb_pwd=password(:col3),mb_email=:col4
                  WHERE mb_adm='Y' AND mb_idx=:col5
              ";
          }

      }

 }
