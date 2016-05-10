<?php 
namespace tests;

use alshf\HunterDog;
use alshf\build\HunterDogException;
use alshf\build\InvalidValueException;
use alshf\channels\feed\BusinessInsider;
use PHPUnit_Framework_TestCase;

class HunterDogTest extends PHPUnit_Framework_TestCase
{	
	public function testGetAllFeedItems()
	{
		try
		{
			$feed = new HunterDog([
				'url' 		=> 'http://www.businessinsider.com/politics/rss',
				'channel' 	=> BusinessInsider::class,
			]);

			$items = $feed->get();

			$this->assertGreaterThan( 0, count($items) );
			
			foreach ($items as $item) 
			{
				$this->assertInstanceOf(BusinessInsider::class, $item);

				try
				{
					$this->assertObjectHasAttribute('namespaces', $item);

					$item->title;
					$item->description;
					$item->link;
					$item->image;
					$item->author;
					$item->publishedAt;
					$item->guid;
				}
				catch ( InvalidValueException $e ) 
				{
					$this->assertInstanceOf(InvalidValueException::class, $e);
				}
			}
		}
		catch ( HunterDogException $e ) 
		{
			$this->assertInstanceOf(HunterDogException::class, $e);
		}
	}
}