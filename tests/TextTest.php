<?php

namespace Chefkoch\Morphoji\Tests;

use Chefkoch\Morphoji\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleEmojiAsPlaceholders() {
        $text = new Text("Whoohoo 🐧");
        $this->assertEquals("Whoohoo &#x1f427;", $text->getWithEntities());
    }

    public function testSimpleEmojiAsUnicode() {
        $text = new Text("Whoohoo &#x1f427;");
        $this->assertEquals("Whoohoo 🐧", $text->getWithEmoji());
    }
}
