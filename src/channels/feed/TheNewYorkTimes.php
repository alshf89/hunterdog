<?php
namespace alshf\channels\feed;

use alshf\channels\FeedProvider as Feed;
use alshf\build\InvalidValueException;
use Sanitizer;

class TheNewYorkTimes extends Feed
{	
	public function title()
	{
		return Sanitizer::bleach( $this->item->title, ['format' => 'utf-8'],
			function( $sponge )
			{
				$sponge->string = preg_replace(
					'/(^live\s*[\:\-\;]|\s*[\:\-\;]\s*live$)/i', '', $sponge->string
				);

				$sponge->string = preg_replace(
					'/(^.{0,50}\s*(\:|\-)|(\-|\:)\s*.{0,50}$)/', '', $sponge->string
				);

				return $sponge->checkLength()
					   		  ->hasKeywords()
					   		  ->hasSpecialchars();
			}
		);
	}

	public function author()
	{
		if( !empty($this->item->children($this->namespaces->dc)->creator) )
		{
			$author = $this->item
						   ->children($this->namespaces->dc)
						   ->creator;

			return ucwords(strtolower($author));
		}
		
		return null;
	}

	public function image()
	{
		if( isset($this->item->children($this->namespaces->media)->content) )
		{
			$image = $this->item
						  ->children($this->namespaces->media)
						  ->content
						  ->attributes()['url'];
			
			if( !Sanitizer::has(['-thumbStandard','.png','nyregion'], $image) )
			{	
				$faults = [
					'-moth' => '-largeHorizontal375',
					'-v1' 	=> '',
					'-v2' 	=> '',
					'-v3' 	=> '',
					'-v4'	=> '',
					'-v5'  	=> ''
				];

				// Images Width & Heigth Sample :
		    	// -moth  	  			Small
		    	// -largeHorizontal375 	Medium
		    	// -videoLarge 			Large
		    	// -jumbo 	  			VeryLarge
				// https://static01.nyt.com/images/2016/05/03/us/04SETUPweb3/04SETUPweb3-moth.jpg
				return str_ireplace(array_keys($faults), array_values($faults), $image);
			}
		}
		
		throw new InvalidValueException('Invalid image value in '.__METHOD__);
	}
}
