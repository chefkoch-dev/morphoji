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
    private $textEntities;

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
    public function getWithEntities()
    {
        if (null === $this->textEntities) {
            $this->textEntities =
                $this->converter->emojiToEntities($this->text);
        }

        return $this->textEntities;
    }

    /**
     * Returns the text with all placeholders converted to emoji.
     *
     * @return null|string
     */
    public function getWithEmoji()
    {
        if (null === $this->textEmoji) {
            $text = $this->getWithEntities();
            $this->textEmoji = $this->converter->entitiesToEmoji($text);
        }

        return $this->textEmoji;
    }
}
