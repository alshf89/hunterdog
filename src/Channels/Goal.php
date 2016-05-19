<?php
namespace alshf\Channels;

use alshf\Build\Feed\RssFeed as Feed;
use alshf\Exceptions\InvalidValueException;
use Sanitizer;

class Goal extends Feed
{	
	protected function image()
	{
		if( isset($this->item->enclosure) )
		{	
			$image = $this->item
						  ->enclosure
						  ->attributes()['url'];

			if( Sanitizer::has('_gallery.jpg', $image) )
			{
				return $image;
			}
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}