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

    /** @var string */
    private static $emojiPattern;
    /** @var string */
    private static $entityPattern;

    /**
     * Converts all unicode emoji in the given text into placeholders.
     *
     * Returns the text with the morphed emoji or null if there was a problem.
     *
     * @param string $text
     * @return string|null
     */
    public function emojiToEntities($text)
    {
        $pattern = $this->getEmojiPattern();

        $latin1Text = preg_replace_callback($pattern, function ($match) {
            $char = array_pop($match);
            $utf32 = mb_convert_encoding($char, 'UTF-32', 'UTF-8');
            $hex = ltrim(bin2hex($utf32), '0');

            return $this->getHtmlEntity($hex);
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
    public function entitiesToEmoji($text)
    {
        $hexPattern = '/^' . $this->getHtmlEntity('([\w\d]{2,8})') . '$/i';

        $unicodeText = preg_replace_callback($this->getEntityPattern(),
            function ($match) use ($hexPattern) {
                $entity = array_pop($match);
                preg_match($hexPattern, $entity, $matches);
                $hex4 = str_pad(array_pop($matches), 8, '0', STR_PAD_LEFT);

                return mb_convert_encoding(hex2bin($hex4), 'UTF-8', 'UTF-32');
            }
        , $text);

        return $unicodeText;
    }

    /**
     * Returns the given hex string as html entity.
     *
     * @param string $hex
     * @return string
     */
    private function getHtmlEntity($hex)
    {
        return "&#x$hex;";
    }

    /**
     * Returns a regular expression pattern to detect emoji characters.
     *
     * @return string
     */
    private function getEmojiPattern()
    {
        if (null === self::$emojiPattern) {
            $codeString = '';

            foreach ($this->getEmojiCodeList() as $code) {
                if (is_array($code)) {

                    $first = dechex(array_shift($code));
                    $last  = dechex(array_pop($code));
                    $codeString .= '\x{' . $first . '}-\x{' . $last . '}';

                } else {
                    $codeString .= '\x{' . dechex($code) . '}';
                }
            }

            self::$emojiPattern = "/[$codeString]/u";
        }

        return self::$emojiPattern;
    }

    /**
     * Returns a regex pattern to detect emoji html entities.
     *
     * @return string
     */
    private function getEntityPattern()
    {
        if (null === self::$entityPattern) {
            $entities = [];

            foreach ($this->getEmojiCodeList() as $code) {
                if (!is_array($code)) {
                    $entities[] = $this->getHtmlEntity(dechex($code));
                } else {
                    foreach($code as $c) {
                        $entities[] = $this->getHtmlEntity(dechex($c));
                    }
                }
            }

            self::$entityPattern = '/' . implode('|', $entities) . '/i';
        }

        return self::$entityPattern;
    }

    /**
     * Returns an array with all unicode values for emoji characters.
     *
     * This is a function so the array can be defined with a mix of hex values
     * and range() calls to conveniently maintain the array with information
     * from the official Unicode tables (where values are given in hex as well).
     *
     * With PHP > 5.6 this could be done in class variable, maybe even a
     * constant.
     *
     * @return array
     */
    private function getEmojiCodeList()
    {
        return [
            // Various 'older' charactes, dingbats etc. which over time have
            // received an additional emoji representation.
            0x203c,
            0x2049,
            0x2122,
            0x2139,
            range(0x2194, 0x2199),
            range(0x21a9, 0x21aa),
            range(0x231a, 0x231b),
            0x2328,
            range(0x23ce, 0x23cf),
            range(0x23e9, 0x23f3),
            range(0x23f8, 0x23fa),
            0x24c2,
            range(0x25aa, 0x25ab),
            0x25b6,
            0x25c0,
            range(0x25fb, 0x25fe),
            range(0x2600, 0x2604),
            0x260e,
            0x2611,
            range(0x2614, 0x2615),
            0x2618,
            0x261d,
            0x2620,
            range(0x2622, 0x2623),
            0x2626,
            0x262a,
            range(0x262e, 0x262f),
            range(0x2638, 0x263a),
            0x2640,
            0x2642,
            range(0x2648, 0x2653),
            0x2660,
            0x2663,
            range(0x2665, 0x2666),
            0x2668,
            0x267b,
            0x267f,
            range(0x2692, 0x2697),
            0x2699,
            range(0x269b, 0x269c),
            range(0x26a0, 0x26a1),
            range(0x26aa, 0x26ab),
            range(0x26b0, 0x26b1),
            range(0x26bd, 0x26be),
            range(0x26c4, 0x26c5),
            0x26c8,
            range(0x26ce, 0x26cf),
            0x26d1,
            range(0x26d3, 0x26d4),
            range(0x26e9, 0x26ea),
            range(0x26f0, 0x26f5),
            range(0x26f7, 0x26fa),
            0x26fd,
            0x2702,
            0x2705,
            range(0x2708, 0x270d),
            0x270f,
            0x2712,
            0x2714,
            0x2716,
            0x271d,
            0x2721,
            0x2728,
            range(0x2733, 0x2734),
            0x2744,
            0x2747,
            0x274c,
            0x274e,
            range(0x2753, 0x2755),
            0x2757,
            range(0x2763, 0x2764),
            range(0x2795, 0x2797),
            0x27a1,
            0x27b0,
            0x27bf,
            range(0x2934, 0x2935),
            range(0x2b05, 0x2b07),
            range(0x2b1b, 0x2b1c),
            0x2b50,
            0x2b55,
            0x3030,
            0x303d,
            0x3297,
            0x3299,

            // Modifier for emoji sequences.
            0x200d,
            0x20e3,
            0xfe0f,

            // 'Regular' emoji unicode space, containing the bulk of them.
            range(0x1f000, 0x1f9cf)
        ];
    }

}
