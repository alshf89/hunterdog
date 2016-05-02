<?php 

// AutoLoad
$loader = require __DIR__ . '/vendor/autoload.php';

use alshf\HunterDog;
use alshf\build\HunterDogException;
use alshf\build\InvalidValueException;

try 
{	
	$RSS = new HunterDog([
		'url' 		=> 'http://cnet.com/rss/news/',
		'channel' 	=> alshf\channels\feed\Atlantic::class,
	]);

	foreach ( $RSS->get() as $item) 
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
			continue;
		}
	}
} 
catch ( HunterDogException $e ) 
{
	echo $e->getMessage();
}


