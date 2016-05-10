<?php
namespace alshf\channels;

use alshf\build\HunterDogException;
use alshf\build\InvalidValueException;
use Sanitizer;
use Carbon\Carbon;

abstract class FeedProvider implements FeedInterface
{
	protected $item;
	protected $namespaces;

	public function __construct( $item, $namespaces )
	{
		$this->item = $item;

		$this->namespaces = new FeedNamespaces( $namespaces );
	}

	public function __get( $key )
	{	
		$value = $this->$key();

		return !empty($value) ? trim($value) : null;
	}

	public function __call( $method, $args )
	{
		throw new HunterDogException('Bad method call : [ '.$method.' ] not exist in '.__CLASS__);
	}

	public function title()
	{
		return Sanitizer::bleach( 
			$this->item->title , [ 'format' => 'utf-8' ], function( $sponge )
			{
				return $sponge->checkLength(15 , 255)
					   		  ->hasKeywords();
			}
		);
	}

	public function description()
	{
		return Sanitizer::bleach( 
			$this->item->description , [ 'format' => 'utf-8' ], function( $sponge )
			{
				return $sponge->shrink()
							  ->checkLength(50 , 255);
			}
		);
	}

	public function link()
	{
		if( !empty($this->item->link) )
		{
			return $this->item->link;
		}
		
		throw new InvalidValueException('Invalid link value in '.__METHOD__);
	}

	public function publishedAt()
	{
		return Carbon::parse( $this->item->pubDate )->format('Y-m-d H:i:s');
	}

	public function author()
	{
		if( !empty($this->item->author) )
		{
			return $this->item->author;
		}
		
		return null;
	}

	public function guid()
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

	abstract public function image();
}