<?php

namespace Chefkoch\Morphoji\Tests;

use Chefkoch\Morphoji\Converter;

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

        $this->assertSame($expect, (new Converter())->fromEmojis("$text $char"));
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

        $this->assertSame($expect, (new Converter())->toEmojis($placeholders));
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

        $this->assertSame($expect, (new Converter())->fromEmojis("$text $char"));
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

        $this->assertSame($expect, (new Converter())->toEmojis($placeholders));
    }

    public function testWrap()
    {
        $text = "Dearly departed ...";
        $placeholder = ":emoji-2122:";

        $wrapped = (new Converter())->wrap($text.' '.$placeholder);

        $this->assertSame("$text <span class=\"emoji\">$placeholder</span>", $wrapped);
    }

    public function testWrapAndReplaceInnerHtml()
    {
        $text = "Dearly departed ...";
        $placeholder = ":emoji-2122:";
        $replace = '&nbsp;';

        $wrapped = (new Converter())->wrap($text.' '.$placeholder, $replace);

        $this->assertSame("$text <span class=\"emoji\">$replace</span>", $wrapped);
    }
}
