<?php
namespace alshf;

use alshf\build\HunterDogException;
use alshf\build\FacadeInterface;

abstract class SadDogFacade implements FacadeInterface
{
	protected static $instance;

	public static function __callStatic( $method , $args ) 
	{

		if ( !static::$instance )
		{
			throw new HunterDogException('Bad static method call : [ '.$method.' ] not exist!');
		}

		if( preg_match( '/error/i', $method ) )
		{
			return call_user_func_array([ static::$instance , $method  ], $args );
		}

		throw new HunterDogException('SadDog can\'t call [ '.$method.' ] method statically!');
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