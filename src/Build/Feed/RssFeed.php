<?php
namespace alshf\Build\Feed;

use Carbon\Carbon;
use Sanitizer;
use alshf\Exceptions\InvalidValueException;

class RssFeed extends FeedProvider
{
	protected function title()
	{
		return Sanitizer::bleach( 
			$this->item->title , [ 'format' => 'utf-8' ], function( $sponge )
			{
				return $sponge->checkLength(15 , 255)
					   		  ->hasKeywords();
			}
		);
	}

	protected function description()
	{
		return Sanitizer::bleach( 
			$this->item->description , [ 'format' => 'utf-8' ], function( $sponge )
			{
				return $sponge->shrink()
							  ->checkLength(50 , 255);
			}
		);
	}

	protected function link()
	{
		if( !empty($this->item->link) )
		{
			return $this->item->link;
		}
		
		throw new InvalidValueException('Invalid link value in '.__METHOD__);
	}

	protected function publishedAt()
	{
		return Carbon::parse( $this->item->pubDate )->format('Y-m-d H:i:s');
	}

	protected function author()
	{
		if( !empty($this->item->author) )
		{
			return $this->item->author;
		}
		
		return null;
	}

	protected function guid()
	{	
		if( !empty($this->item->guid) )
		{
			return md5(trim($this->item->guid));
		}
		elseif( !empty($this->item->link) )
		{
			return md5(trim($this->item->link));
		}

		throw new InvalidValueException('Invalid guid value in '.__METHOD__);
	}

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