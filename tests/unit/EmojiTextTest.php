<?php

namespace Chefkoch\Emoji;


class EmojiTextTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleEmojiAsPlaceholders() {
        $text = new Text("Whoohoo 🐧");
        $this->assertEquals("Whoohoo :emoji-0001f427:", $text->getWithPlaceholders());
    }

    public function testSimpleEmojiAsUnicode() {
        $text = new Text("Whoohoo :emoji-0001f427:");
        $this->assertEquals("Whoohoo 🐧", $text->getWithEmoji());
    }
}
