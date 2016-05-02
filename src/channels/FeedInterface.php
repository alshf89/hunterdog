<?php
namespace alshf\channels;

interface FeedInterface
{
	public function title();

	public function description();

	public function link();

	public function publishedAt();

	public function author();

	public function image();

	public function guid();
}
