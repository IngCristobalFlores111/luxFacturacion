<?php
require 'vendor/autoload.php';

class phpMailerWithGmail{
    private $mailer;

    public function __construct($senderMail,$senderPass,$senderName,$language = 'es'){
        //$launguage = string

        if($senderMail == "" || $senderPass == "" || $senderName == "" || $language == ""){
            $this->__destruct();
            return null;
        }

        $this->mailer = new PHPMailer;

        $this->mailer->setLanguage('es', '../phpMailer/vendor/phpmailer/phpmailer/language');

        //Tell PHPMailer to use SMTP
        $this->mailer->isSMTP();

        //Ask for HTML-friendly debug output
        $this->mailer->Debugoutput = 'html';
        //Set the hostname of the mail server
        $this->mailer->Host = 'smtp.gmail.com';
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $this->mailer->Port = 587;
        //Set the encryption system to use - ssl (deprecated) or tls
        $this->mailer->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $this->mailer->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail

            $this->mailer->Username = $senderMail;
            //Password to use for SMTP authentication

            $this->mailer->Password = $senderPass;
            //Set who the message is to be sent from

            //Create a new PHPMailer instance
            $this->mailer->setFrom($senderMail, $senderName);
            //Set an alternative reply-to address
            $this->mailer->addReplyTo($senderMail, $senderName);
            //Set who the message is to be sent to
    }

    public function sendMail($sentToEmail,$nameSentTo,$Subject,$msgBodyHtml,$msgBody,$arr_attachmentPath = "",$debug=2){
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages

        $this->mailer->CharSet = 'UTF-8';

        $this->mailer->SMTPDebug = $debug;

        date_default_timezone_set('Etc/UTC');

            if(!$this->mailer->addAddress($sentToEmail, $nameSentTo))
                return array("status"=>-1,"error"=>"correo inválido");
        

        //Set the subject line
        $this->mailer->Subject = $Subject;
        //Read an HTML message body from an external file, convert referenced images to embedded,

        //convert HTML into a basic plain-text alternative body
        $this->mailer->msgHTML($msgBodyHtml,'',false);

        //In case the mailer system does not accept html formatted mails, this will take the place of msgHTML.
        $this->mailer->AltBody = $msgBody;

        //Attach an image file
        if(gettype($arr_attachmentPath) == gettype(array())){
            $i = 0;
            $size = sizeof($arr_attachmentPath);
            for($i; $i < $size; $i++){
                if($arr_attachmentPath[$i] != "" && gettype($arr_attachmentPath[$i]) == gettype(""))
                    $this->mailer->addAttachment($arr_attachmentPath[$i]);
            }
        }

        //send the message, check for errors

        if (!$this->mailer->send())
            return array("status" => -1, "error" => $this->mailer->ErrorInfo);
        else
            return array("status" => 0, "error" => "ok");
    }

    public function clearAttachments(){
        $this->mailer->clearAttachments();
    }

    public function __destruct(){
    }
}

?>