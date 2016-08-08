<?php

namespace ieu\Hydrator;

interface HydratorInterface {
	public function hydrate($object, array $data);

	public function extract($object);
}