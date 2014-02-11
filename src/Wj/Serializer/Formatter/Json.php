<?php

namespace Wj\Serializer\Formatter;

use Wj\Serializer\Serializer;
use Metadata\ClassMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Json implements Formatter
{
    private $serializer;

    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function format($object, ClassMetadata $metadata)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $json = array();
        $i = 0;
        $key = $i;

        foreach ($metadata->propertyMetadata as $propertyName => $propertyMetadata) {
            $propertyValue = $accessor->getValue($object, $propertyName);

            if ('key' === $propertyMetadata->map) {
                $key = $propertyValue;

                continue;
            }

            if ('value' === $propertyMetadata->map) {
                $json[$key] = $propertyValue;

                continue;
            }

            if ('[]' === substr($propertyMetadata->type, -2)) {
                $arrayType = substr($propertyMetadata->type, 0, -2);
                $that = $this;
                $value = array_map(function ($v) use ($that) {
                    return $that->parseValue($arrayType, $v);
                });
            } else {
                $value = $this->parseValue($propertyMetadata->type, $propertyValue);
            }

            $json[$key][$propertyName] = $value;
        }

        return json_encode($json);
    }

    private function parseValue($type, $value)
    {
        switch ($type) {
            case 'number':
                $value = intval($value);
                break;

            case 'object':
                $value = json_decode($this->serializer->serialize('json', $value));

                return current($value);

            default:
                $value = $value;
                break;
        }

        return $value;
    }
}
