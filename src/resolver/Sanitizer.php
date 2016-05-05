<?php
namespace alshf\resolver;

use alshf\build\ClosureProvider;
use alshf\build\InvalidValueException;
use alshf\build\HunterDogException;

class Sanitizer
{	
	use ClosureProvider;

	private $chars = [
		'•' 		=> '-',
		'–' 		=> '-',
		'—' 		=> '-',
		'--' 		=> '-',
		'(' 		=> '( ',
		')' 		=> ' )',
		',' 		=> ', ',
		'’' 		=> '\'',
		'‘' 		=> '\'',
		'“' 		=> '"',
		'”' 		=> '"',
		'.,' 		=> '.',
		':' 		=> ' : ',
		'?' 		=> ' ? ',
		'…' 		=> '...',
		'. . .' 	=> '...',
		'   '		=> ' ',
		'  ' 		=> ' '
	];

	private $decodeFlags = ENT_QUOTES | ENT_HTML401 | ENT_XML1 | ENT_XHTML | ENT_HTML5;

	private $string = null;

	private $format = 'utf-8';

	private $keywords = [
		'what time and where',
		'what time does',
		'what channel is it',
		'what time and when does',
		'what time and when is',
		'what time is',
		'what channel does it'
	];

	public function __construct()
	{
		class_alias( SanitizerFacade::class , 'Sanitizer' );

		SanitizerFacade::setInstance( $this );
	}

	public function __set( $key , $value)
	{
		if( property_exists( $this , $key) )
		{
			$this->$key = $value;
		}
	}

	public function __get( $key )
	{
		return $this->$key;
	}

	public function __tostring()
	{
		return $this->string;
	}

	public function __call( $method, $args )
	{
		throw new HunterDogException('Bad method call : [ '.$method.' ] not exist in '.__CLASS__);
	}

	public function bleach( $string , array $options = null , $callable = null )
	{
		$this->string = trim($string);

		if( is_array($options) )
		{
			foreach ($options as $key => $option) 
			{
				$this->$key = $option;
			}
		}

		return $this->fixSpaces()
					->fixNewLinesAndTabs()
					->decode()
					->removeTags()
					->clearInvalidChars()
					->fixQuotes()
					->call( $callable )
					->getString();
	}

	private function fixSpaces()
	{
		$this->string = str_ireplace('&nbsp;', ' ', $this->string);

		return $this;
	}

	private function fixNewLinesAndTabs()
	{
		$this->string = preg_replace('/[\n\t\r]/', ' ', $this->string);

		return $this;
	}

	private function clearInvalidChars()
	{
		$this->string = str_ireplace( 

			array_keys($this->chars), 
			array_values($this->chars), 
			$this->string

		);

		return $this;
	}

	private function removeTags()
	{
		$this->string = strip_tags($this->string);

		return $this;
	}

	private function decode()
	{
		$this->string = html_entity_decode(
			$this->string, $this->decodeFlags, $this->format 
		);
		
		return $this;
	}

	private function fixQuotes()
	{
		if( preg_match('/^(\'|\")|(\'|\")$/', $this->string) == 2 )
		{
			$this->string = preg_replace('/^(\'|\")|(\'|\")$/', '', $this->string);
		}

		$this->string = preg_replace('/\,\s*\'/', '\'', $this->string);
		$this->string = preg_replace('/\,\s*\"/', '"',  $this->string); 

		if( preg_match('/(\'|\")/', $this->string) == 1 && preg_match('/.{2}\s*(\"|\')\s+/', $this->string) )
		{
			$this->string = str_replace([ '\'', '"' ], '', $this->string);
		}

		return $this;
	}

	private function getString()
	{
		if( empty($this->string) )
		{	
			throw new InvalidValueException(
				'Invalid value to sanitize [ '.$this->string.' ] in '.__METHOD__
			);
		}

		return $this->string;
	}

	public function checkLength( $min, $max )
	{
		if( mb_strlen($this->string) <= $min && mb_strlen($this->string) >= $max )
		{
			throw new InvalidValueException(
				'Invalid value length [ '.$length.' ] to sanitize [ '.$this->string.' ] in '.__METHOD__
			);
		}

		return $this;
	}

	public function shrink( $length = 255 )
	{
		preg_match_all('/(?<![A-Z0-9]{1})\.{1,}/', $this->string , $matches , PREG_SET_ORDER | PREG_OFFSET_CAPTURE );

		while ( count($matches) > 0 )
		{
			foreach (array_pop($matches) as $match) 
			{
				if( array_shift($match) == '.' )
				{
					$position = array_shift($match);

					if( $position < $length )
					{
						$this->string = substr($this->string , 0 , $position + 1 );

						return $this;
					}
				}
			}
		}

		throw new InvalidValueException(
			'Value has invalid endpoint to sanitize [ '.$this->string.' ] in '.__METHOD__
		);
	}

	public function has( $needles , $haystack )
	{
	    if( is_array($needles) ) 
	    {
	        foreach ( $needles as $needle ) 
	        {
	            if( is_array($needle) ) 
	            {
	                $position = $this->has( $needle );
	            }
	            else 
	            {
	                $position = stripos( $haystack , $needle );
	            }

	            if ( $position !== false ) return true;
	        }

	        return false;
	    }
	    else
	    {
	        return stripos( $haystack, $needles ) !== false ? true : false;
	    }
	}

	public function hasKeywords( array $keywords = null )
	{
		$keywords = is_array($keywords) ? array_merge($this->keywords,$keywords) : $this->keywords;

		if( $this->has($keywords , $this->string) )
		{
			throw new InvalidValueException(
				'Value contains keywords [ '.implode('|', $keywords).' ] to sanitize [ '.$this->string.' ] in '.__METHOD__
			);
		}

		return $this;
	}

	public function hasSymbols( $haystack )
	{
		return preg_match('/[^A-Z0-9a-z\s\.\,\"\'\!\?]/', $haystack) ? true : false;
	}
}