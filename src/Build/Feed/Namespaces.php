<?php
namespace alshf\Build\Feed;

class Namespaces
{
	public function __construct( array $namespaces )
	{
		if( is_array($namespaces) )
		{
			foreach( $namespaces as $key => $namespace ) 
			{
				$this->$key = $namespace;
			}
		}
	}

	public function __get( $key )
	{
		if( isset($this->$key) )
		{
			return $this->$key;	
		}
		
		return null;
	}

	public function __set( $key , $value )
	{
		$this->$key = $value;
	}
}