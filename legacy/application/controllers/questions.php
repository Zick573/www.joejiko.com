<?php

use Shared\Controller as Controller;
use Framework\ArrayMethods as ArrayMethods;
use Framework\Registry as Registry;
use Framework\RequestMethods as RequestMethods;
use Framework\View as View;

class Questions extends Controller
{
		public function admin()
		{

		}

    public function index()
    {
//				$this->smarty->debugging = true;
				$q = QuestionLegacy::all(array(
					'status = ?' => true
				), array("*"), "id", "desc", 10, 1);

				$questions = array();
				foreach($q as $index => $question)
				{
					$questions[$index]= array(
						'id' => $question->id,
						'question' => $question->question,
						'answer' => $question->answer,
						'date' => $question->question_date,
						'status' => $question->status
					);
				}

				$this->assets->set(array(
					'scripts' => array(
						'libraries/jquery/jquery.colorbox.min' => 'head',
						'libraries/jquery/jquery.timeago' => 'head',
					),
					'styles' => array()
				));

				$this->smarty->assign(array(
					'action' => 'questions/index',
					'meta' => array(
						'title' => "Ask a stupid question, get a smart answer!"
					),
				));

				$this->smarty->assign(array(
					'questions' => $questions
				));
    }

		public function ask()
		{
			if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {

					if(RequestMethods::post("ask"))
					{

						$question = new QuestionLegacy(array(
							'question' => RequestMethods::post("question"),
							'user_email' => RequestMethods::post("email"),
							'user_ip' => $_SERVER['REMOTE_ADDR'],
							'question_date' => date("c"),
							'notify' => RequestMethods::post("notify_me"),
							'status' => 0
						));

						$question->save();

						// @todo get result?
						$this->smarty->assign(array(
							'status' => 200
						));
					}

					$output = $this->smarty->fetch('questions/ask.tpl');

					$this->willRenderLayoutView = false;
					$this->willRenderActionView = false;

					if(RequestMethods::post("format"))
					{
						if(RequestMethods::post("format") == "html")
						{
							$this->willRenderJSON = false;
							$this->smarty->assign('content', $output);
						}
						else
						{
							$this->willRenderJSON = true;
							header("Content-type: application/json");
							$this->smarty->assign('content', json_encode( array('html' => $output) ));
						}
					}
					else
					{
						$this->smarty->assign('content', $output);
					}

					$this->smarty->assign('layoutTpl', 'layouts/blank');
			}
			else
			{

				$this->smarty->assign(array(
					'ask' => true
				));

				self::index();

			}
		}

		public function vote()
		{

			$this->_assets->set(array(
				'styles' => array(
					'questions/vote' => 'all'
				)
			));

			if(array_key_exists('voted', $_COOKIE))
			{
				$voted = json_decode($_COOKIE['voted'], true);
				if(array_key_exists('id', $voted))
				{
					if($voted['id'] == 1)
					{
						// already voted in this poll
						//thanks
						$this->smarty->assign(array(
							'send' => $send,
							'action' => 'questions/vote/thanks',
							'meta' => array(
								'title' => "Thanks for voting!"
							)
						));
					}
				}
			}
			else
			{
				if(RequestMethods::post('gift') || RequestMethods::post('other-gift'))
				{
					if(RequestMethods::post('gift') == 'other')
					{
						if(RequestMethods::post('sex'))
						{
							$sex = RequestMethods::post('sex');
						}
						else
						{
							$sex = "unknown";
						}

						$this->smarty->assign(array(
							'action' => 'questions/vote/extended',
							'sex' => $sex,
							'meta' => array(
								'title' => "What's your favorite Valentines Day gift?"
							)
						));
					}
					else
					{
						// set cookie
						$voted = array('id' => 1);
						setcookie("voted", json_encode($voted), time() + 86400, '/', 'joejiko.com');

						// send it
						self::sendMail();

						//thanks
						$this->smarty->assign(array(
							'send' => $send,
							'action' => 'questions/vote/thanks',
							'meta' => array(
								'title' => "Thanks for voting!"
							)
						));
					}
				}
				else
				{
					if(RequestMethods::post('notify') == '1')
					{
						// send mail
						self::sendMail("New subscription");

						//thanks
						$this->smarty->assign(array(
							'send' => $send,
							'action' => 'questions/vote/notify',
							'meta' => array(
								'title' => "You're subscribed"
							)
						));
					}
					else
					{
						// there can be only one
						$gifts = array('jewelry','teddy bear','roses','chocolate','clothing', 'other');
						$this->smarty->assign("gifts", $gifts);

						$this->smarty->assign(array(
							'action' => 'questions/vote',
							'meta' => array(
								'title' => "What's your favorite Valentines Day gift?"
							)
						));
					}
				}
			}
		}
		public function viewOne($id)
		{
			$this->assets->set(array(
				'scripts' => array(
					'libraries/jquery/jquery.colorbox.min' => 'head',
					'libraries/jquery/jquery.timeago' => 'head',
				),
				'styles' => array()
			));

			$q = QuestionLegacy::first(array(
				"id = ?" => $id
			));

			$question = array(
				'id' => $q->id,
				'question' => $q->question,
				'answer' => $q->answer,
				'date' => $q->question_date
			);

			$this->smarty->assign(array(
				'action' => 'questions/view',
				'meta' => array(
					'title' => $q->question
				)
			));

			$this->smarty->assign(array(
				'question' => $question
			));
		}
		public function view($id)
		{

			$q = QuestionLegacy::first(array(
				"id = ?" => $id
			));

			$question = array(
				'id' => $q->id,
				'question' => $q->question,
				'answer' => $q->answer,
				'date' => $q->question_date
			);

			$this->smarty->assign(array(
				'action' => 'questions/view',
				'meta' => array(
					'title' => "View question"
				)
			));

			$this->smarty->assign(array(
				'question' => $question
			));
		}

		public function sendMail($subject = NULL)
		{
			$transport = Swift_SmtpTransport::newInstance('mail.joejiko.com', 465, 'ssl')
				->setUsername('me@joejiko.com')
				->setPassword('sWG_f{kU@x~v')
				;
			//sWG_f{kU@x~v
			if($subject == NULL)
			{
				// default subject
				$subject = "What's your favorite Valentines Day gift?";
			}

			$settings = array(
				"subject" => $subject,
				"from" => array(
					"me@joejiko.com" => "Form Submission"
				),
				"to" => array(
					"me@joejiko.com" => 'Joe Jiko'
			));

			$this->smarty->assign(array(
				'subject' => $subject,
				'data' => $_POST,
				'user' => $_SERVER['REMOTE_ADDR']
			));

			// fetch email template
			$message = $this->smarty->fetch('contact/emails/vote.tpl');

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
			return $mailer->send($message);
		}

}