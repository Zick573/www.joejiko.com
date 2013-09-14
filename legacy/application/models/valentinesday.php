<?php
class Valentinesday extends Shared\Model
{
	
	public $bank;
	
	public $items;
	
	public $love;
	
	public $restaurants;
	
	public $shops;
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
			$this->bank = $session->bank;
			$this->items = $session->items;
			$this->love = $session->love;
			$this->restaurants = $session->restaurants;
			$this->shops = $session->shops;
		}
		else
		{
			self::init();
		}
		
		return $this;
	}
	public function init()
	{
		// bank
		$starting = rand(0,2000);
		$this->bank = array(
			'starting' => $starting,
			'balance' => $starting
		);

		// love
		$this->love = array(
			'starting' => rand(0,12)
		);

		// items
		$this->items = array(
			'jewelry' => array(
				'label' => 'jewelry',
				'price' => rand(150,600),
				'owned' => 0
			),
			'teddy' => array(
				'label' => 'teddy bear',
				'plural' => 'teddy bears',
				'price' => rand(16,35),
				'owned' => 0
			),
			'roses' => array(
				'label' => 'a dozen roses',
				'plural' => 'dozen roses',
				'price' => rand(36,99),
				'owned' => 0
			),
			'chocolate' => array(
				'label' => 'chocolate',
				'plural' => 'chocolates',
				'price' => rand(1,15),
				'owned' => 0
			),
			'clothing' => array(
				'label' => 'clothes',
				'plural' => 'clothing',
				'price' => rand(50,149),
				'owned' => 0
			)
		);
		
		// restaurants
		$this->restaurants = array(
			'she pays' => array(
				'cost' => 0,
				'love' => -1
			),
			'pb&j' => array(
				'cost' => 1,
				'love' => 0
			),
			'mcdonalds' => array(
				'cost' => 2,
				'love' => 1
			),
			'applebees' => array(
				'cost' => 20,
				'love' => 5
			),
			'olive garden' => array(
				'cost' => 50,
				'love' => 10
			),
			'fancy' => array(
				'cost' => 250,
				'love' => 25
			)
		);
	}
	public function buy()
	{
		// handle on client side?

		// balance > item price

		// +item to items

		// -price from balance

		return $this->bank['balance'];
	}

	public function renderShop()
	{
		// display items in shop
		foreach($this->items as $item => $props)
		{

		}
	}

	public function renderAfterShop()
	{
		// base money

	}

	public function renderRestaurant()
	{
		// base money
	}

	public function renderDinner()
	{
		// base restaurant
	}

	public function renderBedroom()
	{
		// base love
		$love = $this->love['total'];
		switch($love)
		{
			case 0:
				return 0;
			case $love > 0 && $love <= 10:
				return 1;
			case $love > 10 && $love <= 20:
				return 3;
			case $love > 20 && $love <= 30:
				return 4;
			case $love > 30 && $love <= 50:
				return 5;
			case $love > 50:
				return 6;
		}
	}
	
	public function renderOutro()
	{
		// base money
		$money = $this->bank['balance'];
		switch($money)
		{
			case $money < $this->bank['starting'];
				return "-1";
		}
	}
	
	public function renderIntro()
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
}