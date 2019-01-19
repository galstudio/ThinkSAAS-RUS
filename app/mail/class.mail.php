<?php
defined('IN_TS') or die('Access Denied.');

class mail extends tsApp{

	public function __construct($db){
        $tsAppDb = array();
		include 'app/mail/config.php';

		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}

		parent::__construct($db);
	}

	function postMail($sendmail,$subject,$content){

		global $TS_SITE,$tsMySqlCache;

		$options = fileRead('data/mail_options.php');
		if($options==''){
			$options = $tsMySqlCache->get('mail_options');
		}
		date_default_timezone_set('Europe/Moscow');
		require_once 'PHPMailer/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->IsSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug  	= 0;

		$mail->Debugoutput = 'html';

		if($options['ssl']){
			$mail->SMTPSecure = 'ssl';
		}
		$mail->SMTPAuth   	= true;

		$mail->Host       		= $options['mailhost'];
		$mail->Port       		= $options['mailport'];
		$mail->Username   	= $options['mailuser'];
		$mail->Password   	= $options['mailpwd'];

		//POST
		$frommail		= $options['mailuser'];
		$fromname	= $TS_SITE['site_title'];
		$replymail		= $options['mailuser'];
		$replyname	= $TS_SITE['site_title'];
		$sendname	= '';

		if(empty($frommail) || empty($subject) || empty($content) || empty($sendmail)){
			return '0';
		}else{

			$mail->SetFrom($frommail, $fromname);
			$mail->AddReplyTo($replymail,$replyname);
			$mail->Subject    = $subject;
			$mail->AltBody    = "Чтобы просмотреть свою почту, используйте совместимый с HTML почтовый клиент!";
			//$mail->MsgHTML(eregi_replace("[\]",'',$content));
			$mail->MsgHTML(strtr($content,'[\]',''));
			$mail->AddAddress($sendmail, $sendname);
			$mail->send();

			return '1';

		}
	}

	public function __destruct(){

	}

}
