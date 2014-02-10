<?php

namespace Wj\Serializer\Mapping;

use Metadata\PropertyMetadata as BasePropertyMetadata;

class PropertyMetadata extends BasePropertyMetadata
{
    public $type;
    public $map = 'element';
}
