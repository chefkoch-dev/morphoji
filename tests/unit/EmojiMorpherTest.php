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
        $smilieText = "Happy new year! \x{1f600}";
        $this->assertEquals("Happy new year! :emoji-1f600:", $this->morpher->toLatin1Ids($smilieText));
    }

    public function testConvertToSmilie() {
        $smilieText = "Happy new year! \x{1f600}";
        $this->assertEquals("Happy new year! :emoji-1f600:", $this->morpher->toUnicode($smilieText));
    }
}
