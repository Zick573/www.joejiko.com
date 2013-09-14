<?php

use Shared\Controller as Controller;
use Framework\View as View;
use Framework\ArrayMethods as ArrayMethods;
use Framework\RequestMethods as RequestMethods;

class Contact extends Controller
{

    public function index($type=NULL)
    {
      if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {
        if(RequestMethods::post("send"))
        {
          self::_mail();
        }
      }
      else
      {
        if(!is_null($type))
        {
          $allowed = array("general", "new-project", "new-project-request", "request-quote", "request-project", "questions");
          if(in_array($type, $allowed))
          {
            if($type=="general")
            {
              self::redirect('/contact');
              exit();
            }
            $this->smarty->assign(array('type' => $type));
          }
      }

        $this->_assets->setScripts(array(
          'libraries/ckeditor/ckeditor' => 'head'
        ));

        $this->_assets->setStyles(array(
          'contact' => 'all',
          '../scripts/libraries/dojo/dijit/themes/claro/claro' => 'all'
        ));

        $this->smarty->assign(array(
          'action' => 'contact/index',
          'meta' => array(
            'title' => "Contact me"
          )
        ));
      }
    }

    public function _mail()
    {
      $transport = Swift_SmtpTransport::newInstance('mail.joejiko.com', 465, 'ssl')
        ->setUsername('me@joejiko.com')
        ->setPassword('sWG_f{kU@x~v')
        ;
      //sWG_f{kU@x~v
      if(RequestMethods::post("subject"))
      {
        $subject = RequestMethods::post("subject");
      }
      else
      {
        $subject = "(no subject)";
      }
      $from = $_POST['from']['email'];
      if($from && !empty($from) && $from != "")
      {
        $email = $_POST['from']['email'];
      }
      else
      {
        $email = "anon@joejiko.com";
      }

      if($from && !empty($from) && $from != "")
      {
        $name = $_POST['from']['name'];
      }
      else
      {
        $name = "ANON";
      }

      $settings = array(
        "subject" => $subject,
        "from" => array(
          $email => $name
        ),
        "to" => array(
          "me@joejiko.com" => 'Joe Jiko'
      ));

      $this->smarty->assign(array(
        'subject' => $subject,
        'message' => $_POST['message']
      ));

      // fetch email template
      $message = $this->smarty->fetch('contact/emails/general.tpl');

      // Swift
      $mailer = Swift_Mailer::newInstance($transport);
      // Create the message
      $message = Swift_Message::newInstance()

        // Give the message a subject
        ->setSubject($settings["subject"])

        // Set the From address with an associative array
        ->setFrom($settings["from"])

        // Set the To addresses with an associative array
        ->setTo($settings["to"])

        // Give it a body
        ->setBody($message, 'text/html')

        // And optionally an alternative body
        //->addPart('<q>Here is the message itself</q>', 'text/html')

        // Optionally add any attachments
        //->attach(Swift_Attachment::fromPath('my-document.pdf'))
        ;
      $result = $mailer->send($message);
      $this->willRenderLayoutView = false;
      $this->willRenderActionView = false;
      $this->willRenderJSON = true;

      header("Content-type: application/json");
      $this->smarty->assign('content', json_encode( array('data' => $_POST, 'success' => $result) ));
      $this->smarty->assign('layoutTpl', 'layouts/blank');
    }
}