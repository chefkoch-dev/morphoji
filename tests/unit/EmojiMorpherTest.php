<?php

namespace Chefkoch\Emoji;


class EmojiMorpherTest extends \PHPUnit_Framework_TestCase
{

    /** @var Morpher */
    private $morpher;


    protected function setUp() {
        $this->morpher = new Morpher();
    }

    public function testConvertToId() {
        $smilieText = "Happy new year! 😀";
        $this->assertEquals("Happy new year! :emoji-0001f600:", $this->morpher->toPlaceholders($smilieText));
    }

    public function testConvertToSmilie() {
        $smilieText = "Happy new year! :emoji-0001f600:";
        $this->assertEquals("Happy new year! 😀", $this->morpher->toUnicode($smilieText));
    }
}
