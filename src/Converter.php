<?php

namespace Chefkoch\Morphoji;


class Converter implements ConverterInterface, WrapperInterface
{

    const DELIMITER = ':';

    /** @var Detector */
    private static $detector;

    /**
     * Converter constructor.
     *
     * @param Detector $detector
     */
    public function __construct(Detector $detector = null)
    {
        self::$detector = (null === $detector) ? new Detector() : $detector;
    }

    /**
     * @inheritdoc
     */
    public function fromEmojis($text)
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
     * @inheritdoc
     */
    public function toEmojis($text)
    {
        $unicodeText = preg_replace_callback(
            $this->getPlaceholderPattern(),
            function ($match)  {
                $hex4 = str_pad(array_pop($match), 8, '0', STR_PAD_LEFT);
                return mb_convert_encoding(hex2bin($hex4), 'UTF-8', 'UTF-32');
            },
            $text
        );

        return $unicodeText;
    }

    /**
     * By default wraps with span tags with class 'emoji', overridable via
     * $prefix and $postfix variables. Optionally replaces the placeholder
     * between pre- and postfix with the given innerHtml string (e.g.
     * &nbsp; could be a good choice for that).
     *
     * @inheritdoc
     */
    public function wrap($text, $innerHtml = null, $prefix = '<span class="emoji">', $postfix = '</span>')
    {
        $wrappedText = preg_replace_callback(
            $this->getPlaceholderPattern(),
            function ($match) use ($innerHtml, $prefix, $postfix) {
                return $prefix . ($innerHtml ?: array_shift($match)) . $postfix;
            },
            $text
        );

        return $wrappedText;
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
            '%s%s-%s%s', self::DELIMITER, 'emoji', $text, self::DELIMITER);
    }

    /**
     * Returns a regex pattern to find emoji placeholders.
     *
     * @return string
     */
    private function getPlaceholderPattern()
    {
        return '/'.$this->getPlaceholder('([a-f\d]{2,8})').'/i';
    }
}
