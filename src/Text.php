<?php

namespace Chefkoch\Morphoji;

/**
 * Class Text
 *
 * Represents an arbitrary text which may contain emoji characters.
 *
 * The text can be retrived with all emoji characters converted to placeholders
 * or all placeholders converted to emoji characters.
 *
 * @package Chefkoch\Morphoji
 */
class Text
{

    /** @var Converter */
    private $converter;

    /** @var string */
    private $text;

    /** @var string */
    private $textPlaceholders;

    /** @var string */
    private $textEmoji;

    /**
     * Text constructor.
     *
     * @param string $text
     */
    public function __construct($text)
    {
        $this->converter = new Converter();
        $this->text = $text;
    }

    /**
     * Returns the text with all emoji converted to placeholders.
     *
     * @return null|string
     */
    public function getWithPlaceholders()
    {
        if (null === $this->textPlaceholders) {
            $this->textPlaceholders =
                $this->converter->emojiToEntities($this->text);
        }

        return $this->textPlaceholders;
    }

    /**
     * Returns the text with all placeholders converted to emoji.
     *
     * @return null|string
     */
    public function getWithEmoji()
    {
        if (null === $this->textEmoji) {
            $text = $this->getWithPlaceholders();
            $this->textEmoji = $this->converter->entitiesToEmoji($text);
        }

        return $this->textEmoji;
    }
}
