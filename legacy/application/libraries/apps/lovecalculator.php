<?php
namespace Apps
{
	use Framework\Controller as Controller;
	use Framework\Registry as Registry;
	use Framework\Session as Session;
	use Framework\RequestMethods as RequestMethods;
	use Apps\LoveCalculator\Valentine as Valentine;

	class LoveCalculator extends Controller
	{
			public function save($V=NULL)
			{
				// Registry::set("V", json_encode($V));
				if($V !== NULL)
				{
					setcookie("V", json_encode($V), time() + 86400, '/', 'joejiko.com');
				}
			}
			public function loadUser()
			{
				if(array_key_exists("U", $_COOKIE))
				{
					return json_decode($_COOKIE["U"], true);
				}
				else
				{
					return NULL;
				}

			}
			public function load($start = false)
			{
				/*
				how to check if this exists?
					if(Registry::get("V"))
				*/
				if(array_key_exists("V", $_COOKIE) && $start === false)
				{
					return new Valentine(json_decode($_COOKIE["V"], true));
				}
				else
				{
					return new Valentine();
				}
			}

			public function wipe()
			{
				setcookie("V", "", time() -1, '/', 'joejiko.com');
			}

			public function init()
			{
				$V = self::load();
				self::save($V);
				$this->smarty->assign(array(
					'bank' => $V->bank,
					'love' => $V->renderIntro(),
					'lovetotal' => $V->love['total']
				));
			}

			public function hint()
			{
				$V = self::load();
				// hint costs $20
				$this->smarty->assign(array(
					'favorite' => $V->items[$V->favorite]["label"],
					'items' => $V->items
				));
			}

			public function shopBuy()
			{
				$V = self::load();
				if($V->step["checkout"] === false)
				{
					// not checked out yet
					// buy stuff
					$V->buy($_POST);
					self::save($V);
					\Shared\Controller::redirect('/apps/love-calculator/shop');
				}
				else
				{
					// already checked out
					if($V->step["dinner"] === false)
					{
						\Shared\Controller::redirect('/apps/love-calculator/restaurant');
					}
					else
					{
						// already had dinner
						\Shared\Controller::redirect('/apps/love-calculator/finish');
					}
				}
			}

			public function shopCheckout()
			{
				$V = self::load();
				if($V->step["checkout"] === false)
				{
					// do checkout
					$V->step["checkout"] = $V->checkout();
					self::save($V);

					$this->smarty->assign(array(
						"bank" => $V->bank,
						"items" => $V->items,
						"message" => $V->renderCheckout(),
						"love" => $V->love["total"],
						"itemlove" => $V->love["items"]
					));
				}
				else
				{
					// already checked out
					$V = json_decode($_COOKIE["V"], true);
					$this->smarty->assign(array(
						"bank" => $V["bank"],
						"items" => $V["items"],
						"message" => $V["message"],
						"love" => $V["love"]["total"],
						"itemlove" => $V["love"]["items"]
					));
				}
			}

			public function shopView()
			{
				$V = self::load();
				if($V->step["checkout"] === false)
				{
					$this->smarty->assign(array(
						"bank" => $V->bank,
						"items" => $V->items,
						"message" => $V->message,
						"love" => $V->love['total']
					));
				}
				else
				{
					// already checked out
					if($V->step["dinner"] === false)
					{
						\Shared\Controller::redirect('/apps/love-calculator/restaurant');
					}
					else
					{
						// already had dinner
						\Shared\Controller::redirect('/apps/love-calculator/finish');
					}
				}
			}

			public function shop($action=NULL)
			{
				// trying to buy something
				if($action == "buy")
				{
					self::shopBuy();
				}
				// trying to checkout
				elseif($action == "checkout")
				{
					self::shopCheckout();
				}
				// trying to view shop
				else
				{
					self::shopView();
				}
			}

			/* restaurants */
			public function restaurant()
			{
				$V = self::load();
				if($V->step["dinner"] === false)
				{
					// display restaurants
					$this->smarty->assign(array(
						"message" => $V->message,
						"locations" => $V->dinner["locations"],
						"love" => $V->love["total"]
					));
				}
				else
				{
					// already had dinner
					if($V->step["finish"] === true)
					{
						\Shared\Controller::redirect('/apps/love-calculator/finish');
					}

					\Shared\Controller::redirect('/apps/love-calculator/dinner');
				}
			}

			/* dinner story */
			public function dinner()
			{
				$V = self::load();
				if($V->step['dinner'] === false)
				{
					// restaurant chosen
					if($_POST && array_key_exists('location', $_POST))
					{
						// set dinner location from POST
						$dinner = $V->setDinnerLocation($_POST['location']);
						if($dinner === false)
						{
							// error
							self::save($V);
							\Shared\Controller::redirect('/apps/love-calculator/restaurant');
						}

					}
					else
					{
						// set dinner location from memory
						if($V->dinner["choice"] !== NULL)
						{
							$dinner = $V->setDinnerLocation($V->dinner["choice"]);
						}
						else
						{
							// no dinner chosen
							\Shared\Controller::redirect('/apps/love-calculator/restaurant');
						}
					}
				}

				// output story
				$this->smarty->assign(array(
					"dinner" => $V->renderDinner(),
					"love" => $V->love['total']
				));
				self::save($V);
			}
			/* email final result to me */
			public function sendMail($V=NULL)
			{
				$U = self::loadUser();
				if($V !== NULL)
				{
					$transport = \Swift_SmtpTransport::newInstance('mail.joejiko.com', 465, 'ssl')
						->setUsername('me@joejiko.com')
						->setPassword('sWG_f{kU@x~v')
						;
					$settings = array(
						"subject" => "V-Day Love Calculator Results",
						"from" => array(
							"me@joejiko.com" => 'Jikobot'
						),
						"to" => array(
							"me@joejiko.com" => 'Joe Jiko'
					));

					$this->smarty->assign(array(
						'subject' => "V-Day Love Calculator Results",
						'V' => array(
							'bank' => $V->bank,
							'items' => $V->items,
							'love' => $V->love,
							'dinner' => $V->dinner,
							'favorite' => $V->favorite,
							'score' => ($V->love['total'] + $V->love['items']),
							'user' => $_SERVER['REMOTE_ADDR']
						),
						'U' => array(
							'id' => $U['id'],
							'name' => $U['name'],
							'email' => $U['email']
						)
					));

					// fetch email template
					$message = $this->smarty->fetch('contact/emails/love-calculator.tpl');

					// Swift
					$mailer = \Swift_Mailer::newInstance($transport);
					$message = \Swift_Message::newInstance()
						->setSubject($settings["subject"])
						->setFrom($settings["from"])
						->setTo($settings["to"])
						->setBody($message, 'text/html');
					$result = $mailer->send($message);
				}
			}

			public function finish()
			{
				// sum it up
				$V = self::load();
				$U = self::loadUser();

				if($V->step["checkout"] === true
					&& $V->step["dinner"] === true)
				{
					$message = $V->renderFinish();

					if($V->step['finish'] == false)
					{
						// first finish
						self::sendMail($V);

						// save score
						if(!is_int($U['id']))
						{
							$UID = 0;
						}
						else
						{
							$UID = $U['id'];
						}
						$score = new \LoveCalculatorScore(array(
							'score' => ($V->love['total'] + $V->love['items']),
							'user' => $UID,
							'ip' => $_SERVER['REMOTE_ADDR']
						));
						$score->save();

						// mark as finished and save
						$V->step['finish'] = true;
						self::save($V);
					}

					$this->smarty->assign(array(
						'message' => $message,
						'love' => $V->love['total']+$V->love['items'],
						'U' => $U
					));
				}
				else
				{
					if($V->step["checkout"] === false)
					{
						\Shared\Controller::redirect('/apps/love-calculator/shop');
					}

					if($V->step["dinner"] === false)
					{
						\Shared\Controller::redirect('/apps/love-calculator/restaurant');
					}
				}
			}

			public function dump()
			{
				$V = self::load();
				$this->smarty->assign(array(
					"V" => json_decode($_COOKIE["V"], true)
				));
			}
			public function login()
			{

			}

			public function stats()
			{
				$U = self::loadUser();
				if($U['id'] == 1)
				{
					$Users = \User::all(array(
						'service = ?' => "google"
					));
					$Scores = \LoveCalculatorScore::all();

					$this->smarty->assign(array(
						'stats' => array(
							'users' => count($Users),
							'scores' => count($Scores)
						)
					));
				}
				else
				{
					\Shared\Controller::redirect('/apps/love-calculator');
				}
			}

			public function index($request=NULL)
			{
				$V = self::load();
				$U = self::loadUser();

				$scripts = array();

				if($U === NULL || $U['id'] == 0)
				{
					$this->smarty->assign(array(
						'userStatus' => "unknown"
					));
					$scripts['apps/love-calculator/login'] = 'head';
				}
				else
				{
					$this->smarty->assign(array(
						'userStatus' => "logged in"
					));
				}

				switch($request)
				{
					case "/start":
						self::wipe();
						\Shared\Controller::redirect('/apps/love-calculator');
						break;
					case "/shop":
/*				self::shop();
						break;*/
					case "/buy":
/*				self::shop("buy");
						break;*/
					case "/checkout":
					/*
						self::shop("checkout");
						break;
					*/
					case "/restaurant":
					/*
						self::restaurant();
						break;
					*/
					case "/dinner":
					/*
						self::dinner();
						break;
					*/
					case "/finish":
					/*
						self::finish();
						break;
					*/
					case "/dump":
					/*
						self::dump();
						break;
					*/
					case "/hint":
					/*
						self::hint();
						break;
					*/
					case "/login":
					/*
						self::login();
						$scripts['apps/love-calculator/login'] = 'head';
						break;
					*/
					case "/game-over":
						break;
					case "/stats":
						self::stats();
						break;
				}

				if($request === NULL || trim($request) == "")
				{
					$request = "/game-over";
					/*
					$request = "/intro";
					self::init();
					*/
				}

				$this->assets->set(array(
					'scripts' => $scripts,
					'styles' => array(
						'layouts/default/styles' => 'none',
						'apps/love-calculator' => 'all'
					)
				));

				$this->smarty->assign(array(
					'action' => 'apps/love-calculator/index',
					'method' => $this->smarty->fetch('apps/love-calculator'.$request.'.tpl'),
					'meta' => array(
						'title' => "Game over | Valentines Day Love Calculator"
					)
				));
			}
	}
}