<?php
namespace alshf\channels;

use alshf\channels\FeedInterface;
use alshf\channels\FeedNamespaces as FeedNS;
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

		$this->namespaces = new FeedNS( $namespaces );
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
		return Sanitizer::bleach( $this->item->title , [ 'format' => 'utf-8' ], 
			function( $sponge )
			{
				$sponge->string = preg_replace(
					'/(^live\s*[\:\-\;]|\s*[\:\-\;]\s*live$)/i', '', $sponge->string
				);

				return $sponge->checkLength(15)
					   		  ->hasKeywords()
					   		  ->hasSpecialchars();
			}
		);
	}

	public function description()
	{
		return Sanitizer::bleach( $this->item->description , [ 'format' => 'utf-8' ],
			function( $sponge )
			{
				$sponge->string = preg_replace(
					'/(spoiler\s{1}alert\s*\:*|continue\s{1}reading\.*)/i', '', $sponge->string
				);

				return $sponge->checkLength(50);
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