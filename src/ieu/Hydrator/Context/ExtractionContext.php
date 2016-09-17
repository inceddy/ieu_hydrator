<?php

namespace ieu\Hydrator\Context;

/**
 * Extraction context
 *
 * Please note that properties are public for performance reasons
 *
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @licence MIT
 */
class ExtractionContext
{
    /**
     * @var object
     */
    public $object;

    /**
     * The raw properties coming from the object
     * @var [mixed]
     */
    public $raw;
}
