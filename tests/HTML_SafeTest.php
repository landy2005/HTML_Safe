<?php

require_once 'HTML/Safe.php';

use PHPUnit\Framework\TestCase;

final class HTML_SafeTest extends TestCase
{
    /**
     * @dataProvider providerAllowTags
     */
    public function testAllowTags($input, $expected)
    {
        $safe = new HTML_Safe;
        $safe->setAllowTags(array('body'));

        $this->assertSame($expected, $safe->parse($input));
    }
    public function providerAllowTags()
    {
        return array(
            array('<html><body><p>my text</p></body></html>', '<body><p>my text</p></body>'),
        );
    }

    /**
     * @dataProvider providerSpecialChars
     */
    public function testSpecialChars($input, $expected)
    {
        $safe = new HTML_Safe;

        $this->assertSame($expected, $safe->parse($input));
    }

    public function providerSpecialChars()
    {
        return array(
          array('a+b-c',                'a+b-c'),
          array('+49-52 <br />',        '+49-52 <br />'),

          array('<',                    '<'),
          array('>',                    '>'),
          array('&',                    '&'),

          // Entities
          array('&lt;',                 '&lt;'),
          array('&gt;',                 '&gt;'),
          array('&amp;',                '&amp;'),
          // UTF-7
          array('+ADw-',                '<'),
          //array('Star Wars',                     0),
        );
    }
}
