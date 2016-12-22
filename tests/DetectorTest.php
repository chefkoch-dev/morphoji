<?php

namespace Chefkoch\Morphoji;

class DetectorTest extends \PHPUnit_Framework_TestCase
{

    /** @var Detector */
    private $detector;


    public function setUp()
    {
        $this->detector = new Detector();
    }

    public function testGetEmojiPattern()
    {
        $pattern = $this->detector->getEmojiPattern();
        $this->assertNotFalse(@preg_match($pattern, null), 'Is valid regex');
        $this->assertStringEndsWith('u', $pattern, 'Is case insensitive regex');
        $this->assertTrue(strlen($pattern) > 1000, 'Big enough to contain all emoji codes');
    }

    public function testGetEmoji()
    {
        $textWithThreeEmoji = "Morphoji is a tiny PHP library to morph Unicode Emoji characters 🤗 into Latin1 placeholders 🙀 and back. 👍";
        $threeEmoji = ['🤗', '🙀', '👍'];

        $this->assertSame($threeEmoji, $this->detector->getEmoji($textWithThreeEmoji), 'Returns emojis found in string as array');
    }
}
