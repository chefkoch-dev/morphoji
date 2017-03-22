<?php

namespace Chefkoch\Morphoji;

interface WrapperInterface
{
    /**
     * Returns the given text with all emoji placeholder wrapped.
     *
     * If setting a custom $prefix and/or $postfix, the placeholder ':code:' can
     * be used to insert the emojis character code somewhere in the
     * prefix/postfix strings.
     *
     * @param string $text
     * @param null|string $innerHtml
     * @param string $prefix
     * @param string $postfix
     * @return string
     */
    public function wrap($text, $innerHtml = null, $prefix = '', $postfix = '');
}
