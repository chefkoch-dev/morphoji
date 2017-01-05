<?php

namespace Chefkoch\Morphoji;


class EmojiConverter extends Converter
{

    /**
     * @return string
     */
    public function convert()
    {
        $pattern = self::$detector->getEmojiPattern();

        $latin1Text = preg_replace_callback($pattern, function ($match) {
            $char = array_pop($match);
            $utf32 = mb_convert_encoding($char, 'UTF-32', 'UTF-8');
            $hex = ltrim(bin2hex($utf32), '0');

            return $this->getPlaceholder($hex);
        }, $this->text);

        return $latin1Text;
    }
}
