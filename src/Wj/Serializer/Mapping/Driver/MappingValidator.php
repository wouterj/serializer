<?php

namespace Wj\Serializer\Mapping\Driver;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
class MappingValidator
{
    public static function validateType($propertyMapping, $propertyName)
    {
        $acceptedTypes = array(
            'number',
            'string',
            'array',
            'object',
            'number[]',
            'string[]',
            'array[]',
            'object[]'
        );

        if (!in_array($propertyMapping['type'], $acceptedTypes)) {
            throw new \LogicException(sprintf(
                'Type for property "%s" ("%s") must be one of: %s.',
                $propertyName,
                $propertyMapping['type'],
                implode('; ', $acceptedTypes)
            ));
        }
    }

    public static function validateMapType($propertyMapping, $propertyName)
    {
        $acceptedTypes = array('key', 'element', 'attribute', 'value');

        if (!in_array($propertyMapping['map'], $acceptedTypes)) {
            throw new \LogicException(sprintf(
                'Mapping type for property "%s" ("%s") must be one of: %s.',
                $propertyName,
                $propertyMapping['map'],
                implode('; ', $acceptedTypes)
            ));
        }
    }
} 