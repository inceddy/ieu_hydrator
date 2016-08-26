<?php

class Entity {

	private $id;
	private $name;
	private $tags;
	private $camelCase;

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getTags()
	{
		return $this->tags;
	}

	public function getCamelCase()
	{
		return $this->camelCase;
	}
}