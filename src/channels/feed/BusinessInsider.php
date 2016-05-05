<?php
namespace alshf\channels\feed;

use alshf\channels\FeedProvider as Feed;
use alshf\build\InvalidValueException;

class BusinessInsider extends Feed
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

    		// Images Width & Heigth Sample :
    		// http://static2.businessinsider.com/image/563989ac9dd7cc70408bbec8/ex.jpg
			// http://static2.businessinsider.com/image/563989ac9dd7cc70408bbec8-800/ex.jpg
			return substr_replace($image, '-800', strrpos($image, '/'), 0);
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}