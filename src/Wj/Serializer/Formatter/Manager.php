<?php

namespace Wj\Serializer\Formatter;

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
     * @param Formatter[] $formatters
     */
    public function __construct($formatters = array())
    {
        $this->setFormatters($formatters);
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

        return $this->formatters[$format];
    }

    /**
     * @param Formatter[] $formatters
     */
    private function setFormatters($formatters)
    {
        if (0 !== count(array_filter($formatters, function ($formatter) {
            return !$formatter instanceof Formatter;
        }))) {
            throw new \InvalidArgumentException('Formatters must implement the Formatter interface.');
        }

        $this->formatters = $formatters;
    }
}
