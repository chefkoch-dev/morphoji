<?php

namespace Chefkoch\Emoji;


/**
 * Class Morpher
 *
 * Converts texts with Unicode Emoji (https://en.wikipedia.org/wiki/Emoji,
 * http://unicode.org/emoji/index.html) to texts where all Emoji found are
 * replaced with latin1 based placeholders.
 *
 * Default placeholders are of the form `:emoji-HEX_VALUE:` where `HEX_VALUE` is
 * the hex value of the emoji's unicode character.
 *
 * @package Chefkoch\Emoji
 */
class Converter
{

    const DELIMITER = ':';

    /** @var array */
    private $emojiCodes = [
        // Various 'older' charactes, dingbats etc. which over time have
        // have become associated with emoji.
        '\x{203c}',
        '\x{2049}',
        '\x{2122}',
        '\x{2139}',
        '\x{2194}-\x{2199}',
        '\x{21a9}-\x{21aa}',
        '\x{231a}-\x{231b}',
        '\x{2328}',
        '\x{23ce}-\x{23cf}',
        '\x{23e9}-\x{23f3}',
        '\x{23f8}-\x{23fa}',
        '\x{24c2}',
        '\x{25aa}-\x{25ab}',
        '\x{25b6}',
        '\x{25c0}',
        '\x{25fb}-\x{25fe}',
        '\x{2600}-\x{2604}',
        '\x{260e}',
        '\x{2611}',
        '\x{2614}-\x{2615}',
        '\x{2618}',
        '\x{261d}',
        '\x{2620}',
        '\x{2622}-\x{2623}',
        '\x{2626}',
        '\x{262a}',
        '\x{262e}-\x{262f}',
        '\x{2638}-\x{263a}',
        '\x{2640}',
        '\x{2642}',
        '\x{2648}-\x{2653}',
        '\x{2660}',
        '\x{2663}',
        '\x{2665}-\x{2666}',
        '\x{2668}',
        '\x{267b}',
        '\x{267f}',
        '\x{2692}-\x{2697}',
        '\x{2699}',
        '\x{269b}-\x{269c}',
        '\x{26a0}-\x{26a1}',
        '\x{26aa}-\x{26ab}',
        '\x{26b0}-\x{26b1}',
        '\x{26bd}-\x{26be}',
        '\x{26c4}-\x{26c5}',
        '\x{26c8}',
        '\x{26ce}-\x{26cf}',
        '\x{26d1}',
        '\x{26d3}-\x{26d4}',
        '\x{26e9}-\x{26ea}',
        '\x{26f0}-\x{26f5}',
        '\x{26f7}-\x{26fa}',
        '\x{26fd}',
        '\x{2702}',
        '\x{2705}',
        '\x{2708}-\x{270d}',
        '\x{270f}',
        '\x{2712}',
        '\x{2714}',
        '\x{2716}',
        '\x{271d}',
        '\x{2721}',
        '\x{2728}',
        '\x{2733}-\x{2734}',
        '\x{2744}',
        '\x{2747}',
        '\x{274c}',
        '\x{274e}',
        '\x{2753}-\x{2755}',
        '\x{2757}',
        '\x{2763}-\x{2764}',
        '\x{2795}-\x{2797}',
        '\x{27a1}',
        '\x{27b0}',
        '\x{27bf}',
        '\x{2934}-\x{2935}',
        '\x{2b05}-\x{2b07}',
        '\x{2b1b}-\x{2b1c}',
        '\x{2b50}',
        '\x{2b55}',
        '\x{3030}',
        '\x{303d}',
        '\x{3297}',
        '\x{3299}',

        // Modifier for emoji sequences.
        '\x{200d}',
        '\x{20e3}',
        '\x{fe0f}',

        // 'Regular' emoji unicode space, containing the bulk of them.
        '\x{1f000}-\x{1f9cf}'
    ];

    private $prefix;


    /**
     * Morpher constructor.
     *
     * Can be given a custom prefix for the placeholders as well as a delitimer
     * string with which the placeholders will be surrounded.
     *
     * @param string $prefix
     */
    public function __construct($prefix = 'emoji')
    {
        $this->prefix = $prefix;
    }

    /**
     * Converts all unicode emoji in the given text into placeholders.
     *
     * Returns the text with the morphed emoji or null if there was a problem.
     *
     * @param string $text
     * @return string|null
     */
    public function toPlaceholders($text)
    {
        $pattern = $this->getEmojiDetectPattern();

        $latin1Text = preg_replace_callback($pattern, function ($match) {
            $char = array_pop($match);
            $utf32 = mb_convert_encoding($char, 'UTF-32', 'UTF-8');
            $hex4 = bin2hex($utf32);

            return $this->getPlaceholder($hex4);
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
    public function toUnicode($text)
    {
        $regexPattern = '/' . $this->getPlaceholder('[a-f\d]{8}') . '/';

        $unicodeText = preg_replace_callback($regexPattern, function ($match) {
            $code = array_pop($match);
            $hex4 = substr($code, strlen($this->prefix) + 2, 8);

            return mb_convert_encoding(hex2bin($hex4), 'UTF-8', 'UTF-32');
        }, $text);

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
        return sprintf('%s%s-%s%s',
            self::DELIMITER, $this->prefix, $text, self::DELIMITER);
    }

    /**
     * Returns a regular expression pattern to detect emoji characters.
     *
     * @return string
     */
    private function getEmojiDetectPattern()
    {
        return '/[' . implode("", $this->emojiCodes) . ']/u';
    }
}