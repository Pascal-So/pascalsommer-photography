<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Util;

class UtilTest extends TestCase
{
    public function testEscapeMarkdown()
    {
        $this->assertEquals(Util::escapeMarkdown("asdf"), "asdf");
        $this->assertEquals(Util::escapeMarkdown("_asdf_"), "\\_asdf\\_");
        $this->assertEquals(Util::escapeMarkdown("."), "\\.");
        $this->assertEquals(Util::escapeMarkdown("\\"), "\\");
        $this->assertEquals(Util::escapeMarkdown("~"), "\\~");
        $this->assertEquals(Util::escapeMarkdown("[[["), "\\[\\[\\[");
        $this->assertEquals(Util::escapeMarkdown("a]"), "a\\]");
        $this->assertEquals(Util::escapeMarkdown("("), "\\(");
        $this->assertEquals(Util::escapeMarkdown(")"), "\\)");
        $this->assertEquals(Util::escapeMarkdown("`"), "\\`");
        $this->assertEquals(Util::escapeMarkdown("!"), "\\!");
        $this->assertEquals(Util::escapeMarkdown(">"), "\\>");
    }
}
