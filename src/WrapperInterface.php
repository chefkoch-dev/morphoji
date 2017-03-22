<?php

namespace Chefkoch\Morphoji;

interface WrapperInterface
{
    /**
     * Returns the given text with all emoji placeholder wrapped.
     *
     * @param string $text
     * @param null|string $innerHtml
     * @param string $prefix
     * @param string $postfix
     * @return string
     */
    public function wrap($text, $innerHtml = null, $prefix = '', $postfix = '');
}
