<?php namespace Searchable\Interfaces;

interface SearchableResult
{
	public function getTitle();
	public function getImage();
	public function hasImage();
	public function getDescription();
	public function hasDescription();
	public function getLink();
	public function hasLink();
}