<?php

namespace Chefkoch\Morphoji;

/**
 * Class Converter
 *
 * Converts texts with Unicode Emoji (https://en.wikipedia.org/wiki/Emoji,
 * http://unicode.org/emoji/index.html) to texts where all Emoji found are
 * replaced with latin1 based placeholders.
 *
 * Default placeholders are of the form `:emoji-HEX_VALUE:` where `HEX_VALUE` is
 * the hex value of the emoji's unicode character.
 *
 * @package Chefkoch\Morphoji
 */
class Converter
{

    const DELIMITER = ':';
    const PREFIX    = 'emoji';

    /** @var Detector */
    private static $detector;


    /**
     * Converter constructor.
     * @param Detector|null $detector
     */
    public function __construct(Detector $detector = null)
    {
        self::$detector = (null === $detector) ? new Detector() : $detector;
    }

    /**
     * Converts all unicode emoji in the given text into placeholders.
     *
     * Returns the text with the morphed emoji or null if there was a problem.
     *
     * @param string $text
     * @return string|null
     */
    public function emojiToPlaceholders($text)
    {
        $pattern = self::$detector->getEmojiPattern();

        $latin1Text = preg_replace_callback($pattern, function ($match) {
            $char = array_pop($match);
            $utf32 = mb_convert_encoding($char, 'UTF-32', 'UTF-8');
            $hex = ltrim(bin2hex($utf32), '0');

            return $this->getPlaceholder($hex);
        }, $text);

        return $latin1Text;
    }

    /**
     * Converts all placeholders in the given text into unicode emoji.
     *
     * Returns the text with the morphed placeholders or null if there was a
     * problem.
     *
     * @param string $text
     * @return string|null
     */
    public function placeholdersToEmoji($text)
    {
        $placeholderPattern = '/' . $this->getPlaceholder('([a-f\d]{2,8})') . '/i';

        $unicodeText = preg_replace_callback($placeholderPattern,
            function ($match)  {
                $hex4 = str_pad(array_pop($match), 8, '0', STR_PAD_LEFT);

                return mb_convert_encoding(hex2bin($hex4), 'UTF-8', 'UTF-32');
            }
        , $text);

        return $unicodeText;
    }

    /**
     * Returns a placeholder for the given text.
     *
     * The text usually being the hex code for the emoji or the regex needed
     * to find the plaholders.
     *
     * @param string $text
     * @return string
     */
    private function getPlaceholder($text)
    {
        return sprintf(
            '%s%s-%s%s', self::DELIMITER, self::PREFIX, $text, self::DELIMITER);
    }
}
