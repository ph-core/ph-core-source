<?php
namespace Make\Library;

class Mail extends \Make\Database\Pdosql{

    public $tpl = 'default';
    public $t_name;
    public $t_email;
    public $f_email;
    public $f_name;
    public $chk_url;
    public $mb_id ;
    public $mb_pwd ;
    public $subject;
    public $memo;
    public $st_tit;
    public $html;
    public $smtp_sock;
    public $smtp_id;
    public $smtp_pwd;
    public $smtp_server;
    public $smtp_port;

    public function set($arr){
        foreach($arr as $key => $value){
            $this->{$key} = $value;
        }
    }

    public function send(){
        global $CONF;

        $this->scheme('Core\\Scheme');

        $this->f_name = '=?UTF-8?B?'.base64_encode($CONF['title']).'?=';
        $this->f_email = $CONF['email'];
        $this->st_tit = $CONF['title'];
        $this->memo = str_replace('{{name}}',$this->t_name,$this->memo);

        if($this->tpl!=''){
            $this->query(
                $this->scheme->core('select:mailtpl'),
                array(
                    $this->tpl
                )
            );
            $this->specialchars = 0;
            $this->nl2br = 0;
            $arr = $this->fetchs();

            $this->html = str_replace('{{site_title}}',$this->st_tit,$arr['html']);
            $this->html = str_replace('{{check_url}}',$this->chk_url,$this->html);
            $this->html = str_replace('{{id}}',$this->mb_id,$this->html);
            $this->html = str_replace('{{password}}',$this->mb_pwd,$this->html);
            $this->html = str_replace('{{name}}',$this->t_name,$this->html);
        }

        //SMTP 메일서버를 설정한 경우 socket 발송
        if($CONF['use_smtp']=='Y'){
            $this->subject = '=?UTF-8?B?'.base64_encode($this->subject).'?=';
            $this->html = base64_encode(str_replace('{{article}}',$this->memo,$this->html));

            $this->smtp_id = base64_encode($CONF['smtp_id']);
            $this->smtp_pwd = base64_encode($CONF['smtp_pwd']);
            $this->smtp_server = $CONF['smtp_server'];
            $this->smtp_port = $CONF['smtp_port'];

            $this->smtp_sock = fsockopen($this->smtp_server,$this->smtp_port) or die(ERR_MSG_7);
            fputs($this->smtp_sock,"helo ".$this->smtp_server."\r\n");
            fputs($this->smtp_sock,"auth login\r\n");
            fgets($this->smtp_sock,128);
            fputs($this->smtp_sock,$this->smtp_id."\r\n");
            fgets($this->smtp_sock,128);
            fputs($this->smtp_sock,$this->smtp_pwd."\r\n");
            fgets($this->smtp_sock,128);
            fputs($this->smtp_sock,"MAIL FROM: <".$this->f_email.">\r\n");
            fgets($this->smtp_sock,128);
            fputs($this->smtp_sock,"rcpt to: <".$this->t_email.">\r\n");
            fgets($this->smtp_sock,128);
            fputs($this->smtp_sock,"data\r\n");
            fgets($this->smtp_sock,128);
            fputs($this->smtp_sock,"Return-Path: ".$this->f_email."\r\n");
            fputs($this->smtp_sock,"From: ".$this->f_name."<".$this->f_email.">\r\n");
            fputs($this->smtp_sock,"To: <".$this->t_email.">\r\n");
            fputs($this->smtp_sock,"Subject: ".$this->subject."\r\n");
            fputs($this->smtp_sock,"Content-Type: text/html; charset=\"UTF-8\"\r\n");
            fputs($this->smtp_sock,"Content-Transfer-Encoding: base64\r\n");
            fputs($this->smtp_sock,"MIME=Version: 1.0\r\n");
            fputs($this->smtp_sock,"\r\n");
            fputs($this->smtp_sock,$this->html);
            fputs($this->smtp_sock,"\r\n");
            fputs($this->smtp_sock,"\r\n.\r\n");
            fputs($this->smtp_sock,"quit\r\n");
            fclose($this->smtp_sock);

            //SMTP 메일서버가 설정되지 않은 경우 Apache 발송
        }else{
            $this->html = str_replace('{{article}}',$this->memo,$this->html);

            $this->headers  = "MIME=Version: 1.0\r\n";
            $this->headers .= "Content-type:text/html; charset=UTF-8\r\n";
            $this->headers .= "From: ".$this->f_name."<".$this->f_email.">\r\n";
            $this->headers .= "Return-Path: ".$this->f_email."\r\n";
            $this->subject = "=?UTF-8?B?".base64_encode($this->subject)."?=";

            mail($this->t_email,$this->subject,$this->html,$this->headers);
        }
    }
}
