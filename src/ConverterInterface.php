<?php

namespace Chefkoch\Morphoji;

interface ConverterInterface
{
    /**
     * Returns the given text string with all utf-8 emoji characters replaced.
     *
     * Replacement is a latin1 placeholder string containing the emoji character
     * utf-8 code.
     *
     * @param string $text
     * @return string
     */
    public function fromEmojis($text);

    /**
     * Returns the given text with all placeholder converted to utf-8 emojis.
     *
     * Placeholders need to be identical to those returned by self::fromEmojis.
     *
     * @param string $text
     * @return string
     */
    public function toEmojis($text);
}
