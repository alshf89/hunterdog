<?php
namespace alshf\Channels;

use alshf\Build\Feed\RssFeed as Feed;
use alshf\Exceptions\InvalidValueException;
use Sanitizer;

class Quartz extends Feed
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

			if( !Sanitizer::has('.png', $image) )
			{
				$faults = [
					'#038;' 		=> '',
					'quality=80'	=> 'quality=100',
					'w=150' 		=> 'w=940',
				];

	    		// Images Width & Heigth Sample :
	    		// http://qzprod.files.wordpress.com/2015/11/a.jpg?quality=80&#038;strip=all&#038;w=150
    			// http://qzprod.files.wordpress.com/2015/11/a.jpg?quality=100&strip=all&w=600
				return str_ireplace(array_keys($faults), array_values($faults), $image);
			}
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}