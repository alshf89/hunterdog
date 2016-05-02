# HunterDog Feed Readerhttps://github.com/alshf89/hunterdog/issues

A lightweight, expressive and fast Feed Reader. HunterDog takes care of value sanitization. At least PHP 5.4 is required.

## Documentation

 - [Installation](#installation)
 - [How to use](#how-to-use)
 - [Contributing](#contributing)
 - [Credits](#credits)
 - [License](#license)

### Installation

HunterDog uses [Composer](http://getcomposer.org/doc/00-intro.md#installation-nix) to make things easy.

Learn to use composer and add this to require section (in your composer.json):

    "alshf/hunterdog": "1.*"

Or you can use command line:
	
	composer require alshf/hunterdog

And run:

    composer update

Library on [Packagist](https://packagist.org/packages/usmanhalalit/pixie).

### How to use

```PHP
// Make sure you have Composer's autoload file included
require 'vendor/autoload.php';

// Includes
use alshf\HunterDog;
use alshf\build\HunterDogException;
use alshf\build\InvalidValueException;

try 
{	
	// Get Feed from url and and create new instance from channel
	$feed = new HunterDog([
		'url' 		=> 'http://cnet.com/rss/news/',
		'channel' 	=> alshf\channels\feed\Atlantic::class,
	]);

	// Loop throught all items
	foreach ( $feed->get() as $item) 
	{
		try 
		{	
			echo $item->title;
			echo $item->description;
			echo $item->link;
			echo "<img src='".$item->image."'>";
			echo $item->author;
			echo $item->publishedAt;
			echo $item->guid;
		} 
		catch (InvalidValueException $e) 
		{
			//Error on Invalid title, guid or image ...
			continue;
		}
	}
} 
catch ( HunterDogException $e ) 
{	
	// Error
	echo $e->getMessage();
}
```
### Contributing

Bugs and feature request are tracked on [GitHub](https://github.com/alshf89/hunterdog/issues).

### Credits

The code on which this package is principally developed and maintained by [Ali Shafiee](https://github.com/alshf89).

### License

The HunterDog package is released under [the MIT License](LICENSE).