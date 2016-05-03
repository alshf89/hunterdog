<?php
// AutoLoad
$loader = require dirname(__DIR__) . '/vendor/autoload.php';

use alshf\HunterDog;
use alshf\build\HunterDogException;
use alshf\build\InvalidValueException;

try 
{	
	$feed = new HunterDog([
		'url' 		=> 'https://www.theguardian.com/uk/technology/rss',
		'channel' 	=> alshf\channels\feed\TheGuardian::class,
	]);

	foreach ( $feed->get() as $item) 
	{
		try 
		{
			echo "<br>";
			echo $item->title;
			echo "<br>";
			echo $item->description;
			echo "<br>";
			echo $item->link;
			echo "<br>";
			echo "<img src='".$item->image."'>";
			echo "<br>";
			echo $item->author;
			echo "<br>";
			echo $item->publishedAt;
			echo "<br>";
			echo $item->guid;
			echo "<br>";
		} 
		catch (InvalidValueException $e) 
		{
			echo $e->getMessage();
			continue;
		}
	}
} 
catch ( HunterDogException $e ) 
{
	echo $e->getMessage();
}


