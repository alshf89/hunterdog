<?php
namespace alshf\Channels;

use alshf\Build\Feed\RssFeed as Feed;
use alshf\Exceptions\InvalidValueException;

class TheIndependent extends Feed
{	
	protected function image()
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