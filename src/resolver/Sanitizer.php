<?php
namespace alshf\resolver;

use alshf\build\ClosureProvider;
use alshf\resolver\SanitizerFacade;
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

	public function has( $needles , $haystack = null )
	{
		$haystack = !empty($haystack) ? $haystack : $this->string;

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

	public function hasSpecialchars()
	{
		if( preg_match('/(\-{2,}|[\/\\\*\(\)\@\_\+\<\>\|\[\]\{\}\=\;\#]+|\.\s?\.\s?\.)/', $this->string) ) 
		{
			throw new InvalidValueException(
				'Value contains illegal characters to sanitize [ '.$this->string.' ] in '.__METHOD__
			);
		}

		return $this;
	}

	public function checkLength( $length = 15 )
	{
		if( mb_strlen($this->string) <= $length )
		{
			throw new InvalidValueException(
				'Invalid value length [ '.$length.' ] to sanitize [ '.$this->string.' ] in '.__METHOD__
			);
		}

		return $this;
	}

	public function hasKeywords( array $keywords = null )
	{
		$keywords = is_array($keywords) ? array_merge($this->keywords,$keywords) : $this->keywords;

		if( $this->has($keywords) )
		{
			throw new InvalidValueException(
				'Value contains keywords [ '.implode('|', $keywords).' ] to sanitize [ '.$this->string.' ] in '.__METHOD__
			);
		}

		return $this;
	}
}