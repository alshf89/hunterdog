<?php
namespace alshf\build;

interface FacadeInterface
{
	public static function setInstance($instance);

	public static function getInstance();
}