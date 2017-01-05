<?php

namespace Chefkoch\Morphoji;


class PlaceholderConverter extends Converter
{

    /**
     * @return string
     */
    public function convert()
    {
        $placeholderPattern = '/' . $this->getPlaceholder('([a-f\d]{2,8})') . '/i';

        $unicodeText = preg_replace_callback($placeholderPattern,
            function ($match)  {
                $hex4 = str_pad(array_pop($match), 8, '0', STR_PAD_LEFT);

                return mb_convert_encoding(hex2bin($hex4), 'UTF-8', 'UTF-32');
            }
            , $this->text);

        return $unicodeText;
    }
}
