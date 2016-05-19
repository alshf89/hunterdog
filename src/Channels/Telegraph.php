<?php
namespace alshf\Channels;

use alshf\Build\Feed\RssFeed as Feed;
use alshf\Exceptions\InvalidValueException;

class Telegraph extends Feed
{	
	protected function image()
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