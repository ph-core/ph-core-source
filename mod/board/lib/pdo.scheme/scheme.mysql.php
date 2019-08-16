<?php
namespace Module\Board;

class Scheme extends \Make\Database\Pdosql{

    public function conf(){

        return "
            SELECT *
            FROM {$this->table("mod:board_config")}
            WHERE id=:col1
        ";

    }

    public function latest($type){
        global $boardinfo,$orderby,$FETCH_CONF;

        if($type=="select:board"){
            return "
                SELECT *
                FROM {$this->table("mod:board_config")}
                WHERE id=:col1
            ";
        }

        if($type=="select:latest"){
            return "
                SELECT *,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_cmt_".$boardinfo['id'])}
                    WHERE bo_idx=board.idx
                ) comment_cnt,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_like")}
                    WHERE id='{$boardinfo['id']}' AND data_idx=board.idx AND likes>0
                ) likes_cnt
                FROM {$this->table("mod:board_data_".$boardinfo['id'])} board
                WHERE board.use_notice='N' AND board.rn=0 AND board.dregdate IS NULL
                ORDER BY $orderby
                LIMIT {$FETCH_CONF['limit']}
            ";
        }

    }

    public function lists($type){
        global $board_id,$search;

        if($type=="select:notice"){
            return "
                SELECT *,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_cmt_".$board_id)}
                    WHERE bo_idx=board.idx
                ) comment_cnt,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_like")}
                    WHERE id='$board_id' AND data_idx=board.idx AND likes>0
                ) likes_cnt,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_like")}
                    WHERE id='$board_id' AND data_idx=board.idx AND unlikes>0
                ) unlikes_cnt
                FROM {$this->table("mod:board_data_".$board_id)} board
                WHERE board.use_notice='Y'
                ORDER BY board.idx DESC
            ";
        }

        if($type=="select:list"){
            return "
                SELECT *,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_cmt_".$board_id)}
                    WHERE bo_idx=board.idx
                ) comment_cnt,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_like")}
                    WHERE id='$board_id' AND data_idx=board.idx AND likes>0
                ) likes_cnt,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_like")}
                    WHERE id='$board_id' AND data_idx=board.idx AND unlikes>0
                ) unlikes_cnt
                FROM {$this->table("mod:board_data_".$board_id)} board
                WHERE board.use_notice='N' $search
                ORDER BY board.ln DESC, board.rn ASC, board.regdate DESC
            ";
        }

        if($type=="select:member"){
            return "
                SELECT *
                FROM {$this->table("member")}
                WHERE mb_idx=:col1
                LIMIT 1
            ";
        }

        if($type=="select:boards"){
            return "
                SELECT *
                FROM {$this->table("mod:board_config")}
                ORDER BY title ASC
            ";
        }

    }

    public function view($type){
        global $board_id;

        if($type=="select:view"){
            return "
                SELECT
                (
                SELECT COUNT(*)
                FROM {$this->table("mod:board_like")}
                WHERE id='$board_id' AND data_idx=:col1 AND likes>0
                ) likes_cnt,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_like")}
                    WHERE id='$board_id' AND data_idx=:col1 AND unlikes>0
                ) unlikes_cnt,
                board.*
                FROM {$this->table("mod:board_data_".$board_id)} board
                WHERE board.idx=:col1
            ";
        }

        if($type=="select:view2"){
            return "
                SELECT *
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE idx=:col1
            ";
        }

        if($type=="select:parent_mb_idx"){
            return "
                SELECT *
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE ln>:col1 AND rn=:col2
                ORDER BY ln ASC
                LIMIT 1
            ";
        }

        if($type=="update:hit"){
            return "
                UPDATE {$this->table("mod:board_data_".$board_id)}
                SET view = view + 1
                WHERE idx=:col1
            ";
        }

        if($type=="select:likes"){
            return "
                SELECT *
                FROM {$this->table("mod:board_like")}
                WHERE id=:col1 AND data_idx=:col2 AND mb_idx=:col3
            ";
        }

        if($type=="insert:likes"){
            return "
                INSERT INTO {$this->table("mod:board_like")}
                (id,data_idx,mb_idx,likes,unlikes,regdate)
                VALUES
                (:col1,:col2,:col3,1,0,now())
            ";
        }

        if($type=="insert:unlikes"){
            return "
                INSERT INTO {$this->table("mod:board_like")}
                (id,data_idx,mb_idx,likes,unlikes,regdate)
                VALUES
                (:col1,:col2,:col3,0,1,now())
            ";
        }

        if($type=="select:ret_likes"){
            return "
                SELECT
                COUNT(*) total_cnt
                FROM {$this->table("mod:board_like")}
                WHERE id=:col1 AND data_idx=:col2 AND likes>0
            ";
        }

        if($type=="select:ret_unlikes"){
            return "
                SELECT
                COUNT(*) total_cnt
                FROM {$this->table("mod:board_like")}
                WHERE id=:col1 AND data_idx=:col2 AND unlikes>0
            ";
        }

        if($type=="select:files"){
            return "
                SELECT *
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE file1=:col1 OR file2=:col1
            ";
        }

        if($type=="update:file_hit"){
            return "
                UPDATE {$this->table("mod:board_data_".$board_id)}
                SET file1_cnt=file1_cnt+:col1,file2_cnt=file2_cnt+:col2
                WHERE file1=:col3 OR file2=:col3
            ";
        }

    }

    public function comment($type){
        global $board_id;

        if($type=="select:list"){
            return "
                SELECT *
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE bo_idx=:col1
                ORDER BY ln ASC, rn ASC, regdate ASC
            ";
        }

        if($type=="select:cmt_ln"){
            return "
                SELECT MAX(ln)+1000 AS ln_max
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE bo_idx=:col1
            ";
        }

        if($type=="select:cmt_ln2"){
            return "
                SELECT
                IF( MIN(ln)>:col1,MIN(ln),:col2 ) ln
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE ln<:col2 AND ln>:col1 AND rn=:col3 AND bo_idx=:col4
            ";
        }

        if($type=="select:cmt_ln3"){
            return "
                SELECT IF( MAX(ln)>:col1,MAX(ln),:col1 ) ln
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE bo_idx=:col2 AND ln>:col1 AND ln<:col3 AND rn>:col4
            ";
        }

        if($type=="update:cmt_ln"){
            return "
                UPDATE {$this->table("mod:board_cmt_".$board_id)}
                SET ln=ln+1
                WHERE ln<:col1 AND ln>=:col2 AND rn>0
            ";
        }

        if($type=="insert:comment"){
            return "
                INSERT INTO {$this->table("mod:board_cmt_".$board_id)}
                (ln,rn,bo_idx,mb_idx,writer,comment,ip,regdate,cmt_1,cmt_2,cmt_3,cmt_4,cmt_5,cmt_6,cmt_7,cmt_8,cmt_9,cmt_10)
                VALUES
                (:col1,:col2,:col3,:col4,:col5,:col6,'{$_SERVER['REMOTE_ADDR']}',now(),:col7,:col8,:col9,:col10,:col11,:col12,:col13,:col14,:col15,:col16)
            ";
        }

        if($type=="select:comment"){
            return "
                SELECT *
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE idx=:col1
            ";
        }

        if($type=="update:comment"){
            return "
                UPDATE {$this->table("mod:board_cmt_".$board_id)}
                SET writer=:col1,comment=:col2,ip='{$_SERVER['REMOTE_ADDR']}',cmt_1=:col3,cmt_2=:col4,cmt_3=:col5,cmt_4=:col6,cmt_5=:col7,cmt_6=:col8,cmt_7=:col9,cmt_8=:col10,cmt_9=:col11,cmt_10=:col12
                WHERE idx=:col13
            ";
        }

        if($type=="delete:comment"){
            return "
                DELETE
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE idx=:col1
            ";
        }

        if($type=="select:chk_cmt"){
            return "
                SELECT *
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE ln<:col1 AND ln>=:col2 AND rn>=:col3 AND bo_idx=:col4
            ";
        }

    }

    public function write($type){
        global $board_id;

        if($type=="select:data"){
            return "
                SELECT board.*,ceil(board.ln) ceil_ln,
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_data_".$board_id)}
                    WHERE ln<=((ceil_ln/1000)*1000) AND ln>((ceil_ln/1000)*1000)-1000 AND rn>0
                ) reply_cnt
                FROM {$this->table("mod:board_data_".$board_id)} board
                WHERE board.idx=:col1
            ";
        }

        if($type=="select:chk_article"){
            return "
                SELECT *
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE article=:col1
            ";
        }

        if($type=="insert:data"){
            return "
                INSERT INTO {$this->table("mod:board_data_".$board_id)}
                (category,mb_idx,mb_id,writer,pwd,email,article,subject,file1,file2,use_secret,use_notice,use_html,use_email,ip,regdate,ln,rn,data_1,data_2,data_3,data_4,data_5,data_6,data_7,data_8,data_9,data_10)
                VALUES
                (:col1,:col2,:col3,:col4,:col5,:col6,:col7,:col8,:col9,:col10,:col11,:col12,'Y',:col13,'{$_SERVER['REMOTE_ADDR']}',now(),:col14,:col15,:col16,:col17,:col18,:col19,:col20,:col21,:col22,:col23,:col24,:col25)
            ";
        }

        if($type=="select:ln"){
            return "
                SELECT MAX(ln)+1000 AS ln_max
                FROM {$this->table("mod:board_data_".$board_id)}
            ";
        }

        if($type=="select:write_idx"){
            return "
                SELECT idx
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE writer=:col1 AND subject=:col2 AND article=:col3
            ";
        }

        if($type=="select:min_ln"){
            return "
                SELECT *
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE ln>:col1 AND ln<=:col2
            ";
        }

        if($type=="update:data"){
            return "
                UPDATE {$this->table("mod:board_data_".$board_id)}
                SET category=:col1,writer=:col2,pwd=:col3,email=:col4,article=:col5,subject=:col6,file1=:col7,file2=:col8,use_secret=:col9,use_notice=:col10,use_html='Y',use_email=:col11,ip='{$_SERVER['REMOTE_ADDR']}',data_1=:col12,data_2=:col13,data_3=:col14,data_4=:col15,data_5=:col16,data_6=:col17,data_7=:col18,data_8=:col19,data_9=:col20,data_10=:col21
                WHERE idx=:col22
            ";
        }

        if($type=="update:ln"){
            return "
                UPDATE {$this->table("mod:board_data_".$board_id)}
                SET ln=ln-1
                WHERE ln<:col1 AND ln>:col2 AND rn>0
            ";
        }

        if($type=="select:rn"){
            return "
            SELECT rn+1 AS rn_max
            FROM {$this->table("mod:board_data_".$board_id)}
            WHERE idx=:col1
            ";
        }

    }

    public function delete($type){
        global $board_id;

        if($type=="select:ln"){
            return "
                SELECT ln
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE ln>=:col1 AND ln<:col2 AND rn=:col3
                ORDER BY ln DESC
                LIMIT 1
            ";
        }

        if($type=="select:child_data"){
            return "
                SELECT *
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE ln<=:col1 AND ln>:col2 AND rn>=:col3
            ";
        }

        if($type=="delete:comment"){
            return "
                DELETE
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE bo_idx=:col1
            ";
        }

        if($type=="delete:data"){
            return "
                DELETE
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE idx=:col1
            ";
        }

        if($type=="update:data"){
            return "
                UPDATE {$this->table("mod:board_data_".$board_id)}
                SET dregdate=now(),file1='',file2=''
                WHERE idx=:col1
            ";
        }

    }

    public function ctrl($type){
        global $board_id,$t_board_id,$del_where_sum,$ln_where;

        if($type=="select:del_data"){
            return "
                SELECT *
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE $del_where_sum
            ";
        }

        if($type=="delete:comment"){
            return "
                DELETE
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE bo_idx=:col1
            ";
        }

        if($type=="delete:data"){
            return "
                DELETE
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE $del_where_sum
            ";
        }

        if($type=="select:datas"){
            return "
                SELECT *
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE $ln_where
            ";
        }

        if($type=="select:ln_max"){
            return "
                SELECT MAX(ln)+1000 AS ln_max
                FROM {$this->table("mod:board_data_".$t_board_id)}
                ORDER BY ln DESC
                LIMIT 1
            ";
        }

        if($type=="insert:cp_data"){
            return "
                INSERT INTO
                {$this->table("mod:board_data_".$t_board_id)}
                (category,ln,rn,mb_idx,mb_id,writer,pwd,email,article,subject,file1,file1_cnt,file2,file2_cnt,use_secret,use_html,use_email,view,ip,regdate,dregdate,data_1,data_2,data_3,data_4,data_5,data_6,data_7,data_8,data_9,data_10)
                VALUES
                (:col1,:col2,:col3,:col4,:col5,:col6,:col7,:col8,:col9,:col10,:col11,:col12,:col13,:col14,:col15,:col16,:col17,:col18,:col19,now(),:col20,:col21,:col22,:col23,:col24,:col25,:col26,:col27,:col28,:col29,:col30)
            ";
        }

        if($type=="select:cped_idx"){
            return "
                SELECT idx
                FROM {$this->table("mod:board_data_".$t_board_id)}
                WHERE ln=:col1
            ";
        }

        if($type=="update:move_cmnt"){
            return "
                UPDATE
                {$this->table("mod:board_like")}
                SET
                id=:col1,data_idx=:col2
                WHERE id=:col3 AND data_idx=:col4
            ";
        }

        if($type=="select:comment"){
            return "
                SELECT *
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE bo_idx=:col1
            ";
        }

        if($type=="insert:comment"){
            return "
                INSERT INTO
                {$this->table("mod:board_cmt_".$t_board_id)}
                (ln,rn,bo_idx,mb_idx,writer,comment,ip,regdate,cmt_1,cmt_2,cmt_3,cmt_4,cmt_5,cmt_6,cmt_7,cmt_8,cmt_9,cmt_10)
                VALUES
                (:col1,:col2,:col3,:col4,:col5,:col6,:col7,:col8,:col9,:col10,:col11,:col12,:col13,:col14,:col15,:col16,:col17,:col18)
            ";
        }

        if($type=="delete:comment"){
            return "
                DELETE
                FROM {$this->table("mod:board_cmt_".$board_id)}
                WHERE bo_idx=:col1
            ";
        }

    }

    public function manage($type){

        if($type=="sort:config"){
            return "
                SELECT
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:board_config")}
                ) board_total
            ";
        }

        if($type=="select:data_total"){
            global $board_id;
            return "
                SELECT *
                FROM {$this->table("mod:board_data_".$board_id)}
                WHERE dregdate IS NULL AND (use_notice='Y' or use_notice='N')
            ";
        }

        if($type=="select:configs"){
            global $sortby,$searchby,$orderby;
            return "
                SELECT *
                FROM {$this->table("mod:board_config")}
                WHERE 1 $sortby $searchby
                ORDER BY $orderby
            ";
        }

        if($type=="select:config"){
            return "
                SELECT *
                FROM {$this->table("mod:board_config")}
                WHERE id=:col1
                ORDER BY regdate DESC
            ";
        }

        if($type=="select:latest_config"){
            return "
                SELECT *
                FROM {$this->table("mod:board_config")}
                ORDER BY regdate DESC
            ";
        }

        if($type=="select:board"){
            return "
                SELECT *
                FROM {$this->table("mod:board_config")}
                WHERE idx=:col1
                LIMIT 1
            ";
        }

        if($type=="update:board"){
            return "
                UPDATE {$this->table("mod:board_config")}
                SET theme=:col1,title=:col2,use_list=:col3,use_secret=:col4,use_comment=:col5,use_likes=:col6,use_reply=:col7,use_file1=:col8,use_file2=:col9,use_mng_feed=:col10,use_category=:col11,category=:col12,file_limit=:col13,list_limit=:col14,sbj_limit=:col15,txt_limit=:col16,article_min_len=:col17,list_level=:col18,write_level=:col19,secret_level=:col20,comment_level=:col21,delete_level=:col22,read_level=:col23,ctr_level=:col24,reply_level=:col25,write_point=:col26,read_point=:col27,top_source=:col28,bottom_source=:col29,ico_file=:col30,ico_secret=:col31,ico_secret_def=:col32,ico_new=:col33,ico_new_case=:col34,ico_hot=:col35,ico_hot_case=:col36,conf_1=:col37,conf_2=:col38,conf_3=:col39,conf_4=:col40,conf_5=:col41,conf_6=:col42,conf_7=:col43,conf_8=:col44,conf_9=:col45,conf_10=:col46,conf_exp=:col47
                WHERE idx=:col48
            ";
        }

        if($type=="insert:board"){
            return "
                INSERT INTO {$this->table("mod:board_config")}
                (id,theme,title,use_list,use_secret,use_comment,use_likes,use_reply,use_file1,use_file2,use_mng_feed,use_category,category,file_limit,list_limit,sbj_limit,txt_limit,article_min_len,list_level,write_level,secret_level,comment_level,delete_level,read_level,ctr_level,reply_level,write_point,read_point,top_source,bottom_source,ico_file,ico_secret,ico_secret_def,ico_new,ico_new_case,ico_hot,ico_hot_case,regdate,conf_1,conf_2,conf_3,conf_4,conf_5,conf_6,conf_7,conf_8,conf_9,conf_10,conf_exp)
                VALUES
                (:col1,:col2,:col3,:col4,:col5,:col6,:col7,:col8,:col9,:col10,:col11,:col12,:col13,:col14,:col15,:col16,:col17,:col18,:col19,:col20,:col21,:col22,:col23,:col24,:col25,:col26,:col27,:col28,:col29,:col30,:col31,:col32,:col33,:col34,:col35,:col36,:col37,now(),:col38,:col39,:col40,:col41,:col42,:col43,:col44,:col45,:col46,:col47,:col48)
            ";
        }

        if($type=="clone:board"){
            global $board_id,$clone_id,$board_title;
            return "
                INSERT INTO
                {$this->table("mod:board_config")}
                (id,title,regdate,theme,use_list,use_secret,use_comment,use_likes,use_reply,use_file1,use_file2,use_mng_feed,use_category,category,file_limit,list_limit,sbj_limit,txt_limit,article_min_len,list_level,write_level,secret_level,comment_level,delete_level,read_level,ctr_level,reply_level,write_point,read_point,top_source,bottom_source,ico_file,ico_secret,ico_secret_def,ico_new,ico_new_case,ico_hot,ico_hot_case,conf_1,conf_2,conf_3,conf_4,conf_5,conf_6,conf_7,conf_8,conf_9,conf_10,conf_exp)
                SELECT
                (:col1),(:col2),(now()),theme,use_list,use_secret,use_comment,use_likes,use_reply,use_file1,use_file2,use_mng_feed,use_category,category,file_limit,list_limit,sbj_limit,txt_limit,article_min_len,list_level,write_level,secret_level,comment_level,delete_level,read_level,ctr_level,reply_level,write_point,read_point,top_source,bottom_source,ico_file,ico_secret,ico_secret_def,ico_new,ico_new_case,ico_hot,ico_hot_case,conf_1,conf_2,conf_3,conf_4,conf_5,conf_6,conf_7,conf_8,conf_9,conf_10,conf_exp
                FROM {$this->table("mod:board_config")}
                WHERE id=:col3
            ";
        }

        if($type=="delete:board"){
            return "
                DELETE
                FROM {$this->table("mod:board_config")}
                WHERE idx=:col1
            ";
        }

        if($type=="drop:data"){
            global $board_id;
            return "
                DROP TABLE {$this->table("mod:board_data_")}$board_id
            ";
        }

        if($type=="drop:cmt"){
            global $board_id;
            return "
                DROP TABLE {$this->table("mod:board_cmt_")}$board_id
            ";
        }

        if($type=="create:data"){
            global $board_id;
            return "
                CREATE TABLE IF NOT EXISTS {$this->table("mod:board_data_")}$board_id (
                idx int(11) NOT NULL auto_increment,
                category varchar(255) default NULL,
                ln int(11) default '0',
                rn int(11) default '0',
                mb_idx int(11) default '0',
                mb_id varchar(255) default NULL,
                writer varchar(255) default NULL,
                pwd text,
                email varchar(255) default NULL,
                article text,
                subject varchar(255) default NULL,
                file1 text,
                file1_cnt int(11) default '0',
                file2 text,
                file2_cnt int(11) default '0',
                use_secret char(1) default 'N',
                use_notice char(1) default 'N',
                use_html char(1) default 'Y',
                use_email char(1) default 'Y',
                view int(11) default '0',
                ip varchar(255) default NULL,
                regdate datetime default NULL,
                dregdate datetime default NULL,
                data_1 text,
                data_2 text,
                data_3 text,
                data_4 text,
                data_5 text,
                data_6 text,
                data_7 text,
                data_8 text,
                data_9 text,
                data_10 text,
                PRIMARY KEY(idx)
                )ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
        }

        if($type=="create:cmt"){
            global $board_id,$clone_id;
            return "
                CREATE TABLE IF NOT EXISTS {$this->table("mod:board_cmt_")}$board_id (
                idx int(11) NOT NULL auto_increment,
                ln int(11) default '0',
                rn int(11) default '0',
                bo_idx int(11) default NULL,
                mb_idx int(11) default '0',
                writer varchar(255) default NULL,
                comment text,
                ip varchar(255) default NULL,
                regdate datetime default NULL,
                cmt_1 text,
                cmt_2 text,
                cmt_3 text,
                cmt_4 text,
                cmt_5 text,
                cmt_6 text,
                cmt_7 text,
                cmt_8 text,
                cmt_9 text,
                cmt_10 text,
                PRIMARY KEY(idx)
                )ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
        }

    }

}
