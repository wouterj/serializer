<?php

namespace Wj\Serializer\Formatter;

use Metadata\ClassMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Json implements Formatter
{
    public function format($object, ClassMetadata $metadata)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $childs = array();
        $key = 0;

        foreach ($metadata->propertyMetadata as $propertyName => $propertyMetadata) {
            $propertyValue = $accessor->getValue($object, $propertyName);

            if ('key' === $propertyMetadata->map) {
                $key = $propertyValue;

                continue;
            }

            switch ($propertyMetadata->type) {
            case 'number':
                $value = intval($propertyValue);
                break;
            default:
                $value = $propertyValue;
                break;
            }

            $childs[$propertyName] = $value;
        }

        $json = array($key => $childs);

        return json_encode($json);
    }
}
