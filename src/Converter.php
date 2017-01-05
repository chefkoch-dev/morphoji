<?php

namespace Chefkoch\Morphoji;


abstract class Converter implements ConverterInterface
{

    const DELIMITER = ':';
    const PREFIX    = 'emoji';

    /** @var string */
    protected $text;

    /** @var Detector */
    protected static $detector;

    /**
     * Converter constructor.
     * @param string $text
     * @param Detector $detector
     */
    public function __construct($text = '', Detector $detector = null)
    {
        $this->text = $text;
        self::$detector = (null === $detector) ? new Detector() : $detector;
    }

    /**
     * @param string $text
     * @return ConverterInterface
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }

    /**
     * @return string
     */
    abstract public function convert();

    /**
     * Returns a placeholder for the given text.
     *
     * The text usually being the hex code for the emoji or the regex needed
     * to find the plaholders.
     *
     * @param string $text
     * @return string
     */
    protected function getPlaceholder($text)
    {
        return sprintf(
            '%s%s-%s%s', self::DELIMITER, self::PREFIX, $text, self::DELIMITER);
    }
}
