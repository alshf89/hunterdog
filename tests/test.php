<?php
// AutoLoad
$loader = require dirname(__DIR__) . '/vendor/autoload.php';

use alshf\HunterDog;
use alshf\build\HunterDogException;
use alshf\build\InvalidValueException;

try 
{	
	$feed = new HunterDog([
		'url' 		=> 'http://www.businessinsider.com/politics/rss',
		'channel' 	=> alshf\channels\feed\BusinessInsider::class,
	]);

	foreach ( $feed->get() as $item) 
	{
		try 
		{
			$feed = new stdClass;

			$feed->title 	   	= $item->title;
			$feed->description 	= $item->description;
			$feed->link 	   	= $item->link;
			$feed->image 	 	= "<img src='".$item->image."'>";
			$feed->author   	= $item->author;
			$feed->publishedAt  = $item->publishedAt;
			$feed->guid 		= $item->guid;

			dump($feed);

		} 
		catch (InvalidValueException $e) 
		{
			continue;
		}
	}
} 
catch ( HunterDogException $e ) 
{
	echo $e->getMessage();
}


