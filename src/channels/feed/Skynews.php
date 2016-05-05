<?php
namespace alshf\channels\feed;

use alshf\channels\FeedProvider as Feed;
use alshf\build\InvalidValueException;

class Skynews extends Feed
{	
	public function image()
	{
		if( isset($this->item->children($this->namespaces->media)->thumbnail) )
		{
			$image = $this->item
						  ->children($this->namespaces->media)
						  ->thumbnail
						  ->attributes()['url'];

			// Images Width & Heigth Sample :
			// http://media.skynews.com/media/images/generated/2016/4/13/458725/default/v3/11696207-1-1-70x50.jpg
			return str_ireplace("70x50", "536x302", $image);
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}