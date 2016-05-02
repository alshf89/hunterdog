<?php
namespace alshf\channels;

class FeedNamespaces
{
	public function __construct( $namespaces )
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