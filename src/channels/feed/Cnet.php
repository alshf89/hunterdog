<?php
namespace alshf\channels\feed;

use alshf\channels\FeedProvider as Feed;
use alshf\build\InvalidValueException;
use Sanitizer;

class Cnet extends Feed
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
		if( isset($this->item->children($this->namespaces->media)->thumbnail) )
		{
			$image = $this->item
						  ->children($this->namespaces->media)
						  ->thumbnail
						  ->attributes()['url'];

			if( !Sanitizer::has('default.jpg', $image) )
			{
				return $image;
			}
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}