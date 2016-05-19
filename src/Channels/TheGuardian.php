<?php
namespace alshf\Channels;

use alshf\Build\Feed\RssFeed as Feed;
use alshf\Exceptions\InvalidValueException;

class TheGuardian extends Feed
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
			foreach ($this->item->xpath('media:content') as $image) 
			{
				if( $image['width'] > 400 )
				{
					return $image['url'];
				}
			}
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}