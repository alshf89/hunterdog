<?php
namespace alshf\build;

trait ErrorBag
{
	private $errors = [];

	public function errors()
	{	
		$errors = $this->getAllErrors();

		if( is_object($errors) )
		{
			foreach ( $errors as $error ) 
			{
				$this->generateErrorString( $error );
			}
			
			$this->clearErrors();
		}

		return $this->errors;
	}

	public function lastError()
	{	
		$error = $this->getLastError();

		if( is_object($error) )
		{
			$this->generateErrorString( $error );

			$this->clearErrors();
		}

		return array_shift($this->errors);
	}

	private function generateErrorString( $error )
	{
		switch ($error->level) 
		{
	        case LIBXML_ERR_WARNING:
	            $message = "Warning ".$error->code." : ";
	            break;

	         case LIBXML_ERR_ERROR:
	            $message = "Error ".$error->code." : ";
	            break;

	        case LIBXML_ERR_FATAL:
	            $message = "Fatal Error ".$error->code." : ";
	            break;

	        default:
	        	$message = "Error : ";
	        	break;
	    }

	    if( $error->message )
	    {
	    	$message .= trim($error->message);
	    	$message .= " [ Line : ".$error->line." ]";
	    	$message .= " [ Column : ".$error->column." ]";
	    }

	    if( $error->file )
	    {
	    	$message .= " [ File : ".$error->file." ]";
	    }

	    $this->errors[] = $message;
	}

	public function useIntervalErrors( $boolean = true )
	{
		libxml_use_internal_errors( $boolean );
	}

	private function getAllErrors()
	{
		return libxml_get_errors();
	}

	private function getLastError()
	{
		return libxml_get_last_error();
	}

	private function clearErrors()
	{
		libxml_clear_errors();
	}
}