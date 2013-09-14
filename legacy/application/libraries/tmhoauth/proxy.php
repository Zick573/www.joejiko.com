<?php

namespace tmhOAuth
{
    use tmhOAuth\tmhOAuth as tmhOAuth;
		use tmhOAuth\tmhUtilities as tmhUtilities;

    class Proxy
    {

			protected $_tmhOAuth;

			public $error=array();

			public function followers() {
				return $this->_tmhOAuth->request('GET', $this->_tmhOauth->url('followers/ids'));
			}

			public function friends() {

			}

			public function friendships(){

			}

			public function outputError() {
				$this->error = 'There was an error: ' . $this->_tmhOAuth->response['response'];
				return;
			}

			public function wipe() {
				session_destroy();
			}

			public function request_token() {
				$code = $this->_tmhOAuth->request(
					'POST',
					$this->_tmhOAuth->url('oauth/request_token', ''),
					array(
						'oauth_callback' => 'http://joejiko.com/login/twitter/callback'
					)
				);
				if ($code == 200) {
					$_SESSION['oauth'] = $this->_tmhOAuth->extract_params($this->_tmhOAuth->response['response']);
					$this->authorize();
				} else {
					$this->outputError();
				}
			}

			public function callback() {
				self::access_token();
			}

			public function authorize() {
				$authurl = $this->_tmhOAuth->url("oauth/authorize", '') .  "?oauth_token={$_SESSION['oauth']['oauth_token']}";
				header("Location: {$authurl}");

				// in case the redirect doesn't fire
				return '<p>To complete the OAuth flow please visit URL: <a href="'. $authurl . '">' . $authurl . '</a></p>';
			}

			public function access_token() {
				$this->_tmhOAuth->config['user_token']  = $_SESSION['oauth']['oauth_token'];
				$this->_tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

				$code = $this->_tmhOAuth->request(
					'POST',
					$this->_tmhOAuth->url('oauth/access_token', ''),
					array(
						'oauth_verifier' => $_REQUEST['oauth_verifier']
					)
				);

				if ($code == 200) {
					$_SESSION['access_token'] = $this->_tmhOAuth->extract_params($this->_tmhOAuth->response['response']);
					unset($_SESSION['oauth']);
					header('Location: /login/twitter/verify');
				} else {
					$this->outputError($this->_tmhOAuth);
				}
			}

			public function verify_credentials() {
				$this->_tmhOAuth->config['user_token']  = $_SESSION['access_token']['oauth_token'];
				$this->_tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];

				$code = $this->_tmhOAuth->request(
					'GET',
					$this->_tmhOAuth->url('1/account/verify_credentials')
				);

				if ($code == 200) {
					$resp = json_decode($this->_tmhOAuth->response['response']);
					$output = array(
						'service' => 'twitter',
						'id' => $resp->id,
						'screen_name' => $resp->screen_name,
						'name' => $resp->name,
						'location' => $resp->location,
						'access' => $this->_tmhOAuth->response['headers']['x-access-level'],
						'user_token' => $_SESSION['access_token']['oauth_token'],
						'user_secret' => $_SESSION['access_token']['oauth_token_secret']
					);
					return json_encode($output);
				} else {
					outputError();
				}
			}

			public function __construct($user=NULL)
			{

				$this->_tmhOAuth = new tmhOAuth(array(
					'consumer_key'    => '4DqURg8hr6efWqdjOXJQ',
					'consumer_secret' => 'BkBQczseyZtEHjjuKcnUnp2THZ9NOo8Urs4O5RPIs',
					'user_token' => NULL,
					'user_secret' => NULL
				));

			}
		}
}