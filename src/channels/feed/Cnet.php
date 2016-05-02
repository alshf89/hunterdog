<?php
namespace alshf\channels\feed;

use alshf\channels\FeedProvider as Feed;
use alshf\build\InvalidValueException;
use Sanitizer;

class Cnet extends Feed
{	
	public function title()
	{
		return Sanitizer::bleach( $this->item->title, ['format' => 'utf-8'],
			function( $sponge )
			{
				$sponge->string = preg_replace(
					'/(^(cnet|live)\s*[\:\-\;]|\s*[\:\-\;]\s*(cnet|live)$)/i', '', $sponge->string
				);

				return $sponge->checkLength()
					   		  ->hasKeywords()
					   		  ->hasSpecialchars();
			}
		);
	}

	public function author()
	{
		if( !empty($this->item->children($this->namespaces->dc)->creator[0]) )
		{
			return $this->item
						->children($this->namespaces->dc)
						->creator[0];
		}
		
		return null;
	}

	public function image()
	{
		if( isset($this->item->children($this->namespaces->media)->thumbnail[0]) )
		{
			return $this->item
						->children($this->namespaces->media)
						->thumbnail[0]
						->attributes()['url'];	
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}