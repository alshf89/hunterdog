<?php
namespace alshf\Channels;

use alshf\Build\Feed\RssFeed as Feed;
use alshf\Exceptions\InvalidValueException;
use Sanitizer;

class Forbes extends Feed
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
		if( isset($this->item->children($this->namespaces->media)->content) )
		{
			$image = $this->item
						  ->children($this->namespaces->media)
						  ->content
						  ->attributes()['url'];
			
			if( !Sanitizer::has('.png', $image) && Sanitizer::has('specials-images' , $image) )
			{	
				// Images Width & Heigth Sample :
				// http://specials-images.forbesimg.com/imageserve/a/200x0.jpg?fit=scale
				// http://specials-images.forbesimg.com/imageserve/a/600x0.jpg?fit=scale
				// http://specials-images.forbesimg.com/imageserve/a/1200x0.jpg?fit=scale
				return $image;
			}
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}