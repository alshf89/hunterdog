<?php
namespace alshf\Build\Facade;

use alshf\Exceptions\HunterDogException;

class Facade
{
	protected static $instance;

	public static function __callStatic( $method , $args ) 
	{
		if ( !static::$instance )
		{
			throw new HunterDogException('Bad static method call : [ '.$method.' ] not exist!');
		}

		return call_user_func_array([ static::$instance , $method  ], $args );
	}

	public static function setInstance($instance)
	{
		static::$instance  = $instance;
	}

	public static function getInstance()
	{
		return static::$instance;
	}
}