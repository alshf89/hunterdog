<?php
namespace alshf\Channels;

use alshf\Build\Feed\RssFeed as Feed;
use alshf\Exceptions\InvalidValueException;
use Sanitizer;

class CNN extends Feed
{	
	protected function image()
	{
		if( isset($this->item->children($this->namespaces->media)->thumbnail) )
		{
			$image = $this->item
						  ->children($this->namespaces->media)
						  ->thumbnail
						  ->attributes()['url'];

			if( !Sanitizer::has(['.png', 'cnn-logo'], $image) )
			{
				$faults = [
					'-top-tease.jpg'  	=> '-exlarge-169.jpg',
					'-top-tease.jpeg' 	=> '-exlarge-169.jpg',
					'-small-11.jpg' 	=> '-exlarge-169.jpg',
					'-small-169.jpg' 	=> '-exlarge-169.jpg',
					'-large-169.jpg' 	=> '-exlarge-169.jpg'
				];

	    		// Images Width & Heigth Sample :
	    		// http://i2.cdn.turner.com/cnnnext/dam/assets/name-top-tease.jpg
		        // http://i2.cdn.turner.com/cnnnext/dam/assets/name-exlarge-169.jpg
		        // http://i2.cdn.turner.com/cnnnext/dam/assets/name-super-169.jpg
		        // http://i2.cdn.turner.com/cnnnext/dam/assets/name-full-169.jpg
		        // http://i2.cdn.turner.com/cnnnext/dam/assets/name-small-11.jpg
		        // http://i2.cdn.turner.com/cnnnext/dam/assets/name-small-169.jpg
		        // http://i2.cdn.turner.com/cnnnext/dam/assets/name-large-169.jpg
				return str_ireplace(array_keys($faults), array_values($faults), $image);
			}
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}