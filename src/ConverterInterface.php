<?php
/**
 * Created by PhpStorm.
 * User: jens
 * Date: 03/01/2017
 * Time: 01:58
 */

namespace Chefkoch\Morphoji;


interface ConverterInterface
{

    /**
     * @param string $text
     * @return ConverterInterface
     */
    public function setText($text);

    /**
     * @return string
     */
    public function convert();
}
