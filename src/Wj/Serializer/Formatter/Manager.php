<?php

namespace Wj\Serializer\Formatter;

use Wj\Serializer\Serializer;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
class Manager
{
    /**
     * @var Formatter[]
     */
    private $formatters;
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param Formatter[] $formatters
     */
    public function __construct($formatters = array())
    {
        $this->setFormatters($formatters);
    }

    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function add(Formatter $formatter)
    {
        $this->formatters[] = $formatter;
    }

    public function getFormatterByFormat($format)
    {
        if (!isset($this->formatters[$format])) {
            throw new \InvalidArgumentException(sprintf('No formatter found for format "%s".', $format));
        }

        $formatter = $this->formatters[$format];
        $formatter->setSerializer($this->serializer);

        return $formatter;
    }

    /**
     * @param Formatter[] $formatters
     */
    private function setFormatters($formatters)
    {
        array_map(function ($formatter) {
            if (!$formatter instanceof Formatter) {
                throw new \InvalidArgumentException('Formatters must implement the Formatter interface.');
            }

            return $formatter;
        }, $formatters);

        $this->formatters = $formatters;
    }
}
