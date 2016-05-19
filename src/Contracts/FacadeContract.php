<?php
namespace alshf\Contracts;

interface FacadeContract
{
	public static function setInstance($instance);

	public static function getInstance();
}