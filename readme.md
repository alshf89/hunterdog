[![Build Status](https://api.travis-ci.org/alshf89/hunterdog.svg?branch=master)](https://travis-ci.org/alshf/hunterdog)
[![License](https://poser.pugx.org/alshf/hunter-dog/license)](https://packagist.org/packages/alshf/hunter-dog)
[![Total Downloads](https://poser.pugx.org/alshf/hunter-dog/downloads)](https://packagist.org/packages/alshf/hunter-dog)
[![Latest Stable Version](https://poser.pugx.org/alshf/hunter-dog/version)](https://packagist.org/packages/alshf/hunter-dog)
[![Latest Unstable Version](https://poser.pugx.org/alshf/hunter-dog/v/unstable)](//packagist.org/packages/alshf/hunter-dog)

# HunterDog Feed Reader

A lightweight, expressive and fast Feed Reader. HunterDog takes care of value sanitization. At least PHP 5.4 is required.

## Documentation

 - [Installation](#installation)
 - [Channels](#channels)
 - [How to use](#how-to-use)
 	- [Step by Step](#how-to-use)
 	- [Error Handler](#error-handler)
 - [Contributing](#contributing)
 - [Credits](#credits)
 - [License](#license)

### Installation

HunterDog uses [Composer](http://getcomposer.org/doc/00-intro.md#installation-nix) to make things easy.

Learn to use composer and run this Command Line:

    composer require alshf/hunter-dog

### Channels

**HunterDog** provides feed channels and you should use these channels for each feed URLs

#### Available Channels

```PHP
	// Channels
	alshf\channels\feed\CNN::class
	alshf\channels\feed\Cnet::class
	alshf\channels\feed\Goal::class
	alshf\channels\feed\Quartz::class
	alshf\channels\feed\Forbes::class
	alshf\channels\feed\Skynews::class
	alshf\channels\feed\Telegraph::class
	alshf\channels\feed\TheGuardian::class
	alshf\channels\feed\TheIndependent::class
	alshf\channels\feed\BusinessInsider::class
	alshf\channels\feed\EveningStandard::class
	alshf\channels\feed\TheNewYorkTimes::class
```

### How to use

First you should wake HunterDog up and give him a feed **URL** and a **channel** class, he will go and grab all feed items for you!

Make sure you have Composer's autoload file included

```PHP
require 'vendor/autoload.php';
```

#### Step 1.

```PHP
// use HunterDog & Exception
use alshf\HunterDog;
use alshf\build\HunterDogException;

try
{	
	// Create new instance from HunterDog class and pass URL and channel class as an array parameter
	$feed = new HunterDog([
		'url' 		=> 'http://cnet.com/rss/news/',
		'channel' 	=> alshf\channels\feed\Cnet::class,
	]);

	// Get feed
	$feed->get();
}
catch( HunterDogException $e )
{
	// Error
	echo $e->getMessage();
}
```

#### Step 2.

Now you can get title or description or etc from each feed items

```PHP
use alshf\build\InvalidValueException;
```

**Note:** HunterDog will Sanitize it for you so you need to use Sanitizer exception

```PHP
// use Sanitizer exception
use alshf\build\InvalidValueException;

// Loop throught each feed items
foreach ( $feed->get() as $item ) 
{
	try 
	{
		// New intance of stdClass for test
		$feed = new stdClass;

		// Get feed items properties
		$feed->title 	   	= $item->title;
		$feed->description 	= $item->description;
		$feed->link 	   	= $item->link;
		$feed->image 	 	= "<img src='".$item->image."'>";
		$feed->author   	= $item->author;
		$feed->publishedAt  = $item->publishedAt;
		$feed->guid 		= $item->guid;

		print_r($feed);
	} 
	catch (InvalidValueException $e) 
	{	
		// When HunterDog Sanitize each feed item property,
		// Throw exception on invalid string values or images
		continue;
	}
}
```
___

#### Error Handler

you can get all or last XML Error with ErrorBag trait.

```PHP
// SadDog Facade
use SadDog;

// Get all Errors | return an Array of all Errors
SadDog::errors();

// Get last Error | return last Error string
SadDog::lastError();
```
##### Example

```PHP
// use HunterDog & Exception
use alshf\HunterDog;
use alshf\build\HunterDogException;
use SadDog;

try
{	
	// Create new instance from HunterDog class and pass URL and channel class as an array parameter
	$feed = new HunterDog([
		'url' 		=> 'http://cnet.com/rss/news/',
		'channel' 	=> alshf\channels\feed\Cnet::class,
	]);
}
catch( HunterDogException $e )
{
	// use ErrorBag trait
	echo SadDog::lastError();
}
```

### Contributing

Bugs and feature request are tracked on [GitHub](https://github.com/alshf89/hunterdog/issues).

### Credits

The code on which this package is principally developed and maintained by [Ali Shafiee](https://github.com/alshf89).

### License

The HunterDog package is released under [the MIT License](LICENSE.txt).
