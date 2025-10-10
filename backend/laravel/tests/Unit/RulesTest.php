<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App;

class RulesTest extends TestCase
{

    public function testHTMLChecker()
    {
        $class = App::make('App\Rules\NoHTML');

        $this->assertTrue($class->passes('', 'Some normal comment.'));

        $this->assertFalse($class->passes('', 'Some sneaky <a href="http://example.com">linky</a> comment.'));
        $this->assertFalse($class->passes('', 'Some sneaky <script>scripty</script> comment.'));
        $this->assertFalse($class->passes('', 'Some sneaky <img src="asdf"> comment.'));
        $this->assertFalse($class->passes('', 'Some sneaky <style>stylish</style> comment.'));

        $this->assertFalse($class->passes('', 'Some sneaky <     a   href=  "http://example.com">linky comment.'));
    }

    public function testRegexChecker()
    {
        $class = App::make('App\Rules\IsRegex');

        $this->assertTrue($class->isRegex('/.*/'));
        $this->assertTrue($class->isRegex('#.*#'));
        $this->assertTrue($class->isRegex('/.*[abc]/'));
        $this->assertTrue($class->isRegex('/[^abc]/'));

        $this->assertFalse($class->isRegex('/.**/'));
        $this->assertFalse($class->isRegex('asdf'));
        $this->assertFalse($class->isRegex('/.*'));
        $this->assertFalse($class->isRegex('/.*#'));
        $this->assertFalse($class->isRegex('/.*[a/'));
    }
}
