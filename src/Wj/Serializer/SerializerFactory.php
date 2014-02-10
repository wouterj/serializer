<?php

namespace Wj\Serializer;

/**
 * The access point of the Serializer.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class SerializerFactory
{
    public static function createSerializerBuilder()
    {
        return new SerializerBuilder();
    }

    public static function createSerializer()
    {
        return self::createSerializerBuilder()->getSerializer();
    }
}
