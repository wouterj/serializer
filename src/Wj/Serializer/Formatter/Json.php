<?php

namespace Wj\Serializer\Formatter;

use Metadata\ClassMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Json extends AbstractFormatter
{
    public function format($object, ClassMetadata $metadata)
    {
        $json = array();
        $i = 0;
        $key = $i;

        foreach ($metadata->propertyMetadata as $propertyName => $propertyMetadata) {
            $propertyValue = $this->getAccessor()->getValue($object, $propertyName);

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
                $value = array_map(function ($v) use ($that, $arrayType) {
                    return $that->parseValue($arrayType, $v);
                }, $propertyValue);
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
                $value = json_decode($this->getSerializer()->serialize('json', $value));

                return current($value);
        }

        return $value;
    }
}
