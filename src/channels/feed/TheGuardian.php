<?php
namespace alshf\channels\feed;

use alshf\channels\FeedProvider as Feed;
use alshf\build\InvalidValueException;
use Sanitizer;

class TheGuardian extends Feed
{	
	public function author()
	{
		if( !empty($this->item->children($this->namespaces->dc)->creator) )
		{
			return $this->item
						->children($this->namespaces->dc)
						->creator;
		}
		
		return null;
	}

	public function image()
	{
		if( isset($this->item->children($this->namespaces->media)->content) )
		{
			$image = $this->item
						  ->children($this->namespaces->media)
						  ->thumbnail
						  ->attributes();

			if( $image['width'] > 400 )
			{
				return $image['url'];
			}
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}