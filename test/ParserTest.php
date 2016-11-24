<?php
namespace Test\SimpleHtmlToText;

use SimpleHtmlToText\Parser;

require_once(__DIR__ . '/../vendor/autoload.php');

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Simple big test... Much can be improved :)
     */
    public function testBigHtmlBlock()
    {
        $string = file_get_contents('test/files/ParserTest/bigHtmlBlock/input.html');
        $expected = file_get_contents('test/files/ParserTest/bigHtmlBlock/expectedOutput.txt');

        $result = (new Parser())->parseString($string);

        //Please take note of \r\n and \n files. If your result file is \n then it won't match...
        $this->assertEquals($expected, $result);
    }

}