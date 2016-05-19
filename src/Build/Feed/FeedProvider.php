<?php
namespace alshf\Build\Feed;

use alshf\Exceptions\HunterDogException;
use alshf\Exceptions\InvalidValueException;

abstract class FeedProvider
{
	protected $item;
	protected $namespaces;

	public function __construct( $item, $namespaces )
	{
		$this->item = $item;

		$this->namespaces = new Namespaces( $namespaces );
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

	abstract protected function title();

	abstract protected function description();

	abstract protected function link();

	abstract protected function publishedAt();
	
	abstract protected function author();

	abstract protected function guid();
	
	abstract protected function image();
}