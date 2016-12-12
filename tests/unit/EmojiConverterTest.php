<?php

namespace Chefkoch\Emoji;


class EmojiConverterTest extends \PHPUnit_Framework_TestCase
{

    /** @var Converter */
    private $converter;

    private $prefix = 'test';

    protected function setUp() {
        $this->converter = new Converter($this->prefix);
    }

    public function variantEmojiProvider()
    {
        return [
            'tm'       => ['00002122', '™️', '0000fe0f'],
            'skull'    => ['00002620', '☠️', '0000fe0f'],
            'up_arrow' => ['00002b06', '⬆️', '0000fe0f'],
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
            'penguin'           => ['id' => '0001f427', 'char' => '🐧'],
            'crying_w_laughter' => ['id' => '0001f602', 'char' => '😂'],
        ];

    }

    /**
     * @dataProvider variantEmojiProvider
     * @param string $charId
     * @param string $char
     * @param string $modifierId
     */
    public function testConvertEmojiVariantToPlaceholder($charId, $char, $modifierId)
    {
        $text = "Happy new year!";
        $expect = "$text :$this->prefix-$charId::$this->prefix-$modifierId:";

        $this->assertEquals($expect, $this->converter->toPlaceholders("$text $char"));
    }

    /**
     * @dataProvider variantEmojiProvider
     * @param string $charId
     * @param string $char
     * @param string $modifierId
     */
    public function testConvertPlaceholderToEmojiVariant($charId, $char, $modifierId)
    {
        $text = "Happy new year!";
        $expect = "$text $char";
        $placeholders = "$text :$this->prefix-$charId::$this->prefix-$modifierId:";

        $this->assertEquals($expect, $this->converter->toUnicode($placeholders));
    }

    /**
     * @param string $id
     * @param string $char
     * @dataProvider simpleEmojiProvider
     */
    public function testConvertSimpleEmojiToPlacesholder($id, $char)
    {
        $text = "Dearly departed ...";
        $expect = "$text :$this->prefix-$id:";

        $this->assertEquals($expect, $this->converter->toPlaceholders("$text $char"));
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
        $placeholders = "$text :$this->prefix-$id:";

        $this->assertEquals($expect, $this->converter->toUnicode($placeholders));
    }
}
