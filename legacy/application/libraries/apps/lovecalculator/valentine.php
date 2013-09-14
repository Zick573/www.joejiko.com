<?php
namespace Apps\LoveCalculator
{
	class Valentine
	{

		public $bank;

		public $items;

		public $love;

		public $dinner;

		public $shops;

		public $message;

		public $favorite;

		public $step = array(
			'start' => true,
			'checkout' => false,
			'dinner' => false,
			'finish' => false
		);

		public function getLove()
		{
			return $this->love;
		}
		public function getBank()
		{
			return $this->bank;
		}
		public function __construct($session=NULL)
		{
			if($session)
			{
				// setup from session
				$this->bank = $session["bank"];
				$this->items = $session["items"];
				$this->love = $session["love"];
				$this->dinner = $session["dinner"];
				$this->shops = $session["shops"];
				$this->message = $session["message"];
				$this->favorite = $session["favorite"];
				$this->step = $session["step"];
			}
			else
			{
				self::init();
			}

			return $this;
		}
		public function init()
		{

			// love
			$starting = rand(0,12);
			$this->love = array(
				'starting' => $starting,
				'total' => $starting
			);

			// bank
			$crit = ($starting/100)+1;
			$starting = ceil(rand(40,1000)*$crit);
			if($starting < 500)
			{
				if($starting < 100)
				{
					$starting = $starting + 100;
				}
				$starting = $starting + 100;
			}
			$this->bank = array(
				'starting' => $starting,
				'balance' => $starting
			);

			// items
			$this->items = array(
				'jewelry' => array(
					'label' => 'jewelry',
					'price' => rand(150,600),
					'own' => 0,
					'value' => 15
				),
				'teddy' => array(
					'label' => 'teddy bear',
					'plural' => 'teddy bears',
					'price' => rand(16,35),
					'own' => 0,
					'value' => 2
				),
				'roses' => array(
					'label' => 'dozen roses',
					'price' => rand(36,99),
					'own' => 0,
					'value' => 3
				),
				'chocolate' => array(
					'label' => 'chocolate',
					'plural' => 'chocolates',
					'price' => rand(1,15),
					'own' => 0,
					'value' => 1
				),
				'clothing' => array(
					'label' => 'clothes',
					'plural' => 'clothing',
					'price' => rand(50,149),
					'own' => 0,
					'value' => 8
				)
			);

			$this->favorite = $this->selectFavorite();

			// restaurants
			$this->dinner = array(
				'locations' => array(
					'she pays' => array(
						'cost' => 0,
						'love' => -1,
						'message' => 1,
						'img' => 'she-pays'
					),
					'pb&j' => array(
						'cost' => 1,
						'love' => 10,
						'message' => '-1',
						'img' => 'pbj'
					),
					'mcdonalds' => array(
						'cost' => 2,
						'love' => 4,
						'message' => 0,
						'img' => 'mcdonalds'
					),
					'applebees' => array(
						'cost' => 20,
						'love' => 15,
						'message' => 2,
						'img' => 'applebees'
					),
					'olive garden' => array(
						'cost' => 50,
						'love' => 20,
						'message' => 3,
						'img' => 'olive-garden'
					),
					'fancy' => array(
						'cost' => 250,
						'love' => 35,
						'message' => 4,
						'img' => 'fancy'
					)
				),
				'choice' => NULL
			);
		}

		public function selectFavorite()
		{
			$items = $this->items;
			$item_count = (count($items) - 1);
			$rand = rand(0, $item_count);
			if(is_int($rand))
			{
				$keys = array_keys($this->items);
				return $keys[$rand];
			}

			return NULL;
		}

		public function checkout()
		{
			// @todo add message for bonus items
			$crit = (rand(1,100)/100)+1;
			$this->message = "";
			$items = $this->items;
			$love = 0;
			foreach($items as $name => $props)
			{
				if($name == $this->favorite)
				{
					// critical love
					$love = $love + ceil(($crit*$props['value'])*$props['own'])+5;
				}
				else
				{
					$love = $love+($props['value']*$props['own']);
				}
			}
			$this->love['items'] = $love;
			return true;
		}

		public function buy($post)
		{
			if(is_array($post))
			{
				foreach($post as $name => $value)
				{
					if(array_key_exists($name, $this->items))
					{
						$balance = $this->bank["balance"];
						$price = $this->items[$name]["price"];

						if( ($balance - $price) > 0)
						{
							$this->bank["balance"] = (int)$balance - (int)$price;
							$this->items[$name]["own"]++;
							$this->message = "bought 1 ".$this->items[$name]["label"];
						}
						else
						{
							$this->message = "You don't have enough money!";
						}
					}
				}
			}
			else
			{
				$this->message("post is not an array!");
			}
			return $this;
		}

		public function setDinnerLocation($location = NULL)
		{
			if($location != NULL)
			{
				$choice = $location;
				if(array_key_exists($choice, $this->dinner["locations"]))
				{
					$location = $this->dinner["locations"][$choice];
					$balance = $this->bank["balance"];
					$cost = $location["cost"];
					$love = $location["love"];
					if($balance - $cost >= 0)
					{
						$this->dinner["choice"] = $location["message"];
						$this->bank["balance"] = $this->bank["balance"] - $cost;
						$this->love["total"] = $this->love["total"] + $love;
						$this->step["dinner"] = true;
						return true;
					}
					else
					{
						$this->message = "You can't afford this place.";
						return false;
					}
				}
				else
				{
					$this->message = "Invalid dinner location";
					return false;
				}
			}

			$this->message = "Choose a location";
			return false;
		}

		public function getCheckoutStory()
		{
			// base money left
			$money = $this->bank["balance"];
			switch($money)
			{
				case $money < 0:
					return '-1';
				case $money == $this->bank["starting"]:
					return 0;
				case $money < 100:
					return 1;
				case $money > 100 && $money < 800:
					return 2;
				case $money > 800:
					return 3;
			}
		}

		public function renderCheckout()
		{
			$message = $this->getStory('checkout', $this->getCheckoutStory());
			$message = str_replace("[BALANCE]", $this->bank['balance'], $message);
			return $message;
		}

		public function getBedroomStory()
		{
			// spending bonus
			if($this->bank['starting'] == $this->bank['balance'])
			{
				$spent_percent = 100;
			}
			else
			{
				$spent_percent = ($this->bank['balance']/$this->bank['starting'])*100;
			}

			// turns her off if you didn't spend at least half
			if($spent_percent < 50)
			{
				$this->love['total'] = ceil($this->love['total']/2);
			}

			// base love
			$love = ceil($this->love['total'] + $this->love['items']);
			switch($love)
			{
				case 0:
					return 0;
				case $love > 0 && $love <= 16:
					return 0;
				case $love > 16 && $love <= 23:
					return 1;
				case $love > 23 && $love <= 29:
					return 2;
				case $love > 29 && $love <= 36:
					return 3;
				case $love > 36 && $love <= 47:
					return 4;
				case $love > 47 && $love <= 56:
				 return 5;
				case $love > 56 && $love <= 68:
					return 6;
				case $love > 68 && $love <= 80:
				  return 7;
				case $love > 80:
				  return 8;
			}
		}
		public function renderDinner()
		{
			if($this->dinner["choice"] !== NULL)
			{
				return $this->getStory('dinner', $this->dinner["choice"]);
			}
			else
			{
				// no location set
				return false;
			}
		}
		public function renderFinish()
		{
			$message = "";
			$message .= $this->getStory('finish', $this->getBedroomStory());
			// base money
			$money = $this->bank['balance'];
			$money_spent = $this->bank['starting'] - $money;
			switch($money)
			{
				case $this->bank['balance'] < 0;
					$message .= $this->getStory('cost', '-1');
					break;
				case $money_spent > 1000:
					$message .= $this->getStory('cost', 0);
					break;
			}

			$message = str_replace("[MONEY_SPENT]", $money_spent, $message);
			return $message;
		}

		public function renderIntro()
		{
			return $this->getStory('starting love', $this->getIntroStory());
		}

		public function getIntroStory()
		{
			// starting love message
			$starting = $this->love['starting'];
			switch($this->love['starting'])
			{
				case 0:
					return 0;
				case $starting > 0 && $starting <=5:
					return 1;
				case $starting > 5 && $starting < 9:
					return 2;
				case $starting >= 9:
					return 3;
			}
		}

		public function getStory($key, $index)
		{
			$message = array(
				'starting love' => array(
					0 => "no",
					1 => " a little",
					2 => "a generous amount of",
					3 => " a whole lotta"
				),
				'checkout' => array(
					0 => "You're definitely not getting laid tonight.",
					1 => "You're broke. Only $[BALANCE] left.",
					2 => "You're feeling the burn in your wallet, but you've still got $[BALANCE]",
					3 => "You've got a flourishing money tree in the backyard. This day had no impact on you financially. Still got a cool $[BALANCE] in the bank!",
					'-1' => "But you had to borrow money from your mom and now you're broke.. You owe your mom: $[BALANCE] Better go mow the lawn or something."
				),
				'dinner' => array(
					0 => "you walk to the McDonalds down the street and let her order something nice off the Dollar Menu.",
					1 => "You tell her you're broke and let her know that she'll have to pay if she wants to go out for dinner tonight. Of course she wants to go out to dinner.. but she's not happy with having to do the Man's job of paying. You have your mom drop you off at the restaurant she choses.",
					2 => "You pick her up and take her to Applebees. Dinner tastes a little bland, but you can't beat the 2 for $20! Still, she could've dressed up a little. Jean shorts? Come on!",
					3 => "You pick her up and take her out to Olive Garden and enjoy a fine dinner. The conversation is fairly dull, but you can't help but notice she's wearing a really slutty dress. She's caught you staring at her over-exposed cleavage twice.. and doesn't seem to mind!",
					4 => "You've got everything planned out. You pick her up, wearing a tailored suit, and head out to a fancy restaurant you made a reservation to in advance. She's wearing a short skirt and a looooong jacket. Her eyes flutter every time she looks at you. You order a nice glass of Hacienda Merlot and you can tell she's really feeling relaxed.",
					'-1' => "You invited her over for peanut butter & jelly sandwiches. She didn't sound too happy on the phone, but she agreed to come over. You eat your sandwiches while watching television. Conversation is limited."
				),
				'finish' => array(
					0 => "What girlfriend? Bitches hate you.",
					1 => "She hurries to the door and closes it quickly behind her.. better luck next year.",
					2 => "As you're leaving her house to go home, you notice the car of one of her guy friend's in the driveway. He has been around a lot lately.. You probably should've bought a hooker with that $[MONEY_SPENT]",
					3 => "She gives you a kiss and tells you goodnight but doesn't invite you in. Looks like it's Mr. Hand for you tonight buddy! You spent $[MONEY_SPENT] on a kiss and a self-given hand job. Was it worth it?",
					4 => "She makes you work really hard for hours in foreplay that only leads to casual, missionary-style sex. She gets dressed afterwards, shows you to the door, and tells you goodnight. You sleep at home alone. Oh well, at least you got to hit that! Probably wasn't worth the $[MONEY_SPENT] bucks, huh?",
					5 => "She leads the way to the bedroom and is undressed before she gets there.. motioning you forward. The sex is multiple and passionate. You both fall asleep naked, her head resting on your chest. Good job buddy! It only cost you $[MONEY_SPENT]",
					6 => "She begs you to let her spend the night at your house. She brings toys.. and gives up the butt. But you already knew that would happen, didn't you Mr. Pimp? ;)",
					7=> "You’ve barely shut the door and she has you up against a wall. Her body is pressed against yours and she hums in anticipation. She holds a rope to your hands and urges you to tie her down. \"It’s all you tonight, baby, do whatever you want with me!\" Several hours later, you're both out of breath and fully relaxed. Totally worth the $[MONEY_SPENT]",
					8=> "You arrive at her house and as you approach the door she stops you, leans in close, and whispers \"I got you a surprise!\". She slowly opens the door, revealing a group of her hot, slutty friends! She says, \"You get to play with all of us tonight, you've earned it.\" Best. Valentine's Day. Ever! Doesn't even matter that it cost $[MONEY_SPENT]"
				),
				'cost' => array(
					0 => " Yeah, she probably would've given it up for less.",
					'-1' => " Don't forget to thank your mom for lending you money!"
				)
			);

			return $message[$key][$index];
		}

	}
}