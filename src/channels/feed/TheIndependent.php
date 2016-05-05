<?php
namespace alshf\channels\feed;

use alshf\channels\FeedProvider as Feed;
use alshf\build\InvalidValueException;

class TheIndependent extends Feed
{	
	public function image()
	{
		if( isset($this->item->children($this->namespaces->media)->thumbnail) )
		{
			return $this->item
						  ->children($this->namespaces->media)
						  ->thumbnail
						  ->attributes()['url'];
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}