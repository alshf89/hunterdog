<?php
namespace alshf\channels\feed;

use alshf\channels\FeedProvider as Feed;
use alshf\build\InvalidValueException;
use Sanitizer;

class Telegraph extends Feed
{	
	public function image()
	{
		if( isset($this->item->enclosure) )
		{
			return $this->item
						->enclosure
						->attributes()['url'];
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}