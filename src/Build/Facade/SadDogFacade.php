<?php
namespace alshf\Build\Facade;

use alshf\Contracts\FacadeContract;
use alshf\Exceptions\HunterDogException;

class SadDogFacade extends Facade implements FacadeContract
{
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
}