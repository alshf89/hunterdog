<?php
namespace alshf;

use SimpleXMLElement;
use Exception;
use alshf\build\HunterDogException;
use alshf\build\ErrorBag;
use alshf\SadDogFacade;
use alshf\resolver\Sanitizer;

class HunterDog
{
	use ErrorBag;

	private $xml;
	private $url;
	private $namespace = "";
	private $options   = LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_NOCDATA;
	private $isDataUrl = true;
	private $hasPrefix = false;
	private $channel;
	private $alias = "SadDog";
	private $items;

	public function __construct( array $options )
	{

		if( is_array($options) )
		{
			foreach ( $options as $key => $option ) 
			{
				if( property_exists($this, $key) )
				{
					$this->$key = $option;
				}
			}
		}

		$this->setErrorFacadeInstance();

		try  
		{
			new Sanitizer;

			$this->useIntervalErrors();

			$this->loadXML();
				 
			$this->validateAdaptor();

			$this->setXpathNamespaces();

		}
		catch( Exception $e )
		{	
			throw new HunterDogException( $e->getMessage() , null, $e );
		}
	}

	private function loadXML()
	{
		// if( $this->checkURL() )
		// {
			$this->xml = new SimpleXMLElement(
				$this->url , $this->options , $this->isDataUrl , $this->namespace , $this->hasPrefix
			);
		// }
		// else
		// {
			// throw new HunterDogException('Please use valid XML link.');
		// }
	}

	private function checkURL()
	{	

		$handler = curl_init();

		curl_setopt_array( $handler, [

			CURLOPT_URL				=> $this->url,
			CURLOPT_RETURNTRANSFER 	=> true,
			CURLOPT_NOBODY 			=> true,
			CURLOPT_HEADER 			=> true,
			CURLOPT_TIMEOUT 		=> 5,
			CURLOPT_FOLLOWLOCATION	=> true,
			CURLOPT_MAXREDIRS 		=> 1

		]);

		curl_exec($handler);

		if( !curl_error($handler) )
		{
			switch( curl_getinfo($handler, CURLINFO_HTTP_CODE) ) 
			{
				case 200:
					$isValid = true;
					break;
				
				default:
					$isValid = false;
					break;
			}
		}

		curl_close($handler);

		return isset($isValid) ? $isValid : false;
	}

	private function setErrorFacadeInstance()
	{
		class_alias( SadDogFacade::class , $this->alias );

		SadDogFacade::setInstance($this);
	}

	private function validateAdaptor()
	{
		if( !is_object($this->xml) || !$this->xml instanceof SimpleXMLElement )
		{
			throw new HunterDogException('HunterDog can\'t get XML content from URL : ['.$this->url.']');
		}
	}

	private function setXpathNamespaces()
	{
		foreach ( $this->getNamespaces() as $key => $value)
		{
			$this->xml->registerXPathNamespace($key, $value);
		}
	}

	private function getNamespaces()
	{
		return $this->xml->getDocNamespaces();
	}

	public function get()
	{	
		foreach ($this->xml->channel->xpath('item') as $item)
		{
			$this->items[] = new $this->channel( $item , $this->getNamespaces() );
		}

		return $this->items;
	}
}