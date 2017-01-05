<?php

namespace Chefkoch\Morphoji;

/**
 * Class Detector
 *
 * Offers the unicode codes of emoji characters as well as a regex pattern based
 * on them. Can also extract all emoji characters from a given string.
 *
 * @package Chefkoch\Morphoji
 */
class Detector
{

    /** @var string */
    private static $emojiPattern;

    /**
     * @param string $string
     * @return array
     */
    public function getEmoji($string)
    {
        preg_match_all($this->getEmojiPattern(), $string, $matches);

        return isset($matches[0]) ? array_pop($matches) : [];
    }

    /**
     * Returns a regular expression pattern to detect emoji characters.
     *
     * @return string
     */
    public function getEmojiPattern()
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
    public function getEmojiCodeList()
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
