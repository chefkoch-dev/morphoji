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
     * @param string $prefix optional, default is 'emoji'
     */
    public function __construct($text, $prefix = 'emoji')
    {
        $this->converter = new Converter($prefix);
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
                $this->converter->emojiToPlaceholders($this->text);
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
            $this->textEmoji = $this->converter->placeholdersToEmoji($text);
        }

        return $this->textEmoji;
    }
}
