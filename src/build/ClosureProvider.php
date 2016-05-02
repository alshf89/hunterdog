<?php
namespace alshf\build;

use Closure;
use alshf\build\HunterDogException;

trait ClosureProvider
{
	public function call( Closure $callable = null )
	{
		if( $callable instanceof Closure )
		{
			if( is_callable($callable) )
			{
				return call_user_func($callable , $this);
			}

			throw new HunterDogException('Please create valid and callable closure.');
		}

		return $this;
	}
}