<?php

namespace Chefkoch\Morphoji\Tests;

use Chefkoch\Morphoji\EmojiConverter;
use Chefkoch\Morphoji\PlaceholderConverter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

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
        $converter = new EmojiConverter("$text $char");

        $this->assertSame($expect, $converter->convert());
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
        $converter = new PlaceholderConverter($placeholders);

        $this->assertSame($expect, $converter->convert());
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
        $converter = new EmojiConverter();

        $this->assertSame(
            $expect, $converter->setText("$text $char")->convert());
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
        $converter = new PlaceholderConverter();

        $this->assertSame($expect, $converter->setText($placeholders)->convert());
    }
}
