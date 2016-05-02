<?php
namespace alshf\channels\feed;

use alshf\channels\FeedProvider as Feed;
use alshf\build\InvalidValueException;
use Sanitizer;

class Goal extends Feed
{	
	public function image()
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