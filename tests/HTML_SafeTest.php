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
          array('+ADwAPAA8-',           '<<<'),
          //array('+AGAAfgAhAEAAIwAkACUAXgAmACo()+AF8AKw--+AD0AewB9AHwAWwBdAFw:+ACIAOw\'+ADwAPg?,./', '`~!@#$%^&*()_+-={}|[]\:";\'<>?,./'),
        );
    }

    /**
     * @dataProvider providerPreventXSS
     */
    public function testPreventXSS($input, $expected)
    {
        $safe = new HTML_Safe;

        $this->assertSame($expected, $safe->parse($input));
    }

    public function providerPreventXSS()
    {
        return array(
          array('<span style="width: expression(alert(\'Ping!\'));"></span>', '<span></span>'),
          array('<script>alert(\'xss\')</script>',                              ''),
          array('+ADw-script+AD4-alert(+ACc-xss+ACc-)+ADw-+AC8-script+AD4-',    ''),
          //
          array('+ADw-p+AD4-Welcome to UTF-7!+ADw-+AC8-p+AD4-
+ADw-script+AD4-alert(+ACc-utf-7!+ACc-)+ADw-+AC8-script+AD4-',                    "<p>Welcome to UTF-7!</p>\n"),
          //array('>',                    '>'),
          //array('&',                    '&'),

        );
    }
}
