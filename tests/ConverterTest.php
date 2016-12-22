<?php

namespace Chefkoch\Morphoji\Tests;

use Chefkoch\Morphoji\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    /** @var Converter */
    private $converter;

    protected function setUp() {
        $this->converter = new Converter();
    }

    public function variantEmojiProvider()
    {
        return [
            'tm'       => ['™️', '2122', 'fe0f'],
            'skull'    => ['☠️', '2620', 'fe0f'],
            'up_arrow' => ['⬆️', '2b06', 'fe0f'],
        ];
    }

    /**
     * IMPORTANT: the 'char' values are NOT SPACES. If they appear to be, your
     * editor or IDE (e.g. Phpstorm) isn't displaying 4 Byte Unicode characters
     * correctly.
     *
     * @return array
     */
    public function simpleEmojiProvider()
    {
        return [
            'penguin'           => ['1f427', '🐧'],
            'crying_w_laughter' => ['1f602', '😂'],
        ];

    }

    /**
     * @dataProvider variantEmojiProvider
     * @param string $charId
     * @param string $char
     * @param string $modId
     */
    public function testConvertEmojiVariantToPlaceholder($char, $charId, $modId)
    {
        $text = "Happy new year!";
        $expect = "$text :emoji-{$charId}::emoji-{$modId}:";

        $this->assertEquals($expect, $this->converter->emojiToPlaceholders("$text $char"));
    }

    /**
     * @dataProvider variantEmojiProvider
     * @param string $charId
     * @param string $char
     * @param string $modId
     */
    public function testConvertPlaceholderToEmojiVariant($char, $charId, $modId)
    {
        $text = "Happy new year!";
        $expect = "$text $char";
        $placeholders= "$text :emoji-{$charId}::emoji-{$modId}:";

        $this->assertEquals($expect, $this->converter->placeholdersToEmoji($placeholders));
    }

    /**
     * @param string $id
     * @param string $char
     * @dataProvider simpleEmojiProvider
     */
    public function testConvertSimpleEmojiToPlaceholder($id, $char)
    {
        $text = "Dearly departed ...";
        $expect = "$text :emoji-$id:";

        $this->assertEquals($expect, $this->converter->emojiToPlaceholders("$text $char"));
    }

    /**
     * @param string $id
     * @param string $char
     * @dataProvider simpleEmojiProvider
     */
    public function testConvertPlaceholderToSimpleEmoji($id, $char)
    {
        $text = "Dearly departed ...";
        $expect = "$text $char";
        $placeholders = "$text :emoji-$id:";

        $this->assertEquals($expect, $this->converter->placeholdersToEmoji($placeholders));
    }
}
