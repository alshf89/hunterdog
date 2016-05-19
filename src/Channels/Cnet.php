<?php
namespace alshf\Channels;

use alshf\Build\Feed\RssFeed as Feed;
use alshf\Exceptions\InvalidValueException;
use Sanitizer;

class Cnet extends Feed
{	
	protected function author()
	{
		if( !empty($this->item->children($this->namespaces->dc)->creator) )
		{
			return $this->item
						->children($this->namespaces->dc)
						->creator;
		}
		
		return null;
	}

	protected function image()
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