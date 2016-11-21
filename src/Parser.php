<?php
namespace SimpleHtmlToText;

class Parser {
    /** @var  string */
    private $html;

    /** @var  string */
    private $text;

    /**
     * @param $string
     * @return string
     */
    public function parseString($string) {
        $this->setHtml($string);
        $this->parse();
        return $this->getText();
    }

    /**
     * Parse the HTML and put it into the text variable
     */
    private function parse() {
        $string = $this->getHtml();

        //Remove HTML's whitespaces
        $string = preg_replace('/\s+/', ' ', $string);


        //Parse links
        $string = preg_replace('/<a(.*)href=[\'"](.*)[\'"]>(.*)<\/a>/Uis', '$3 ($2)', $string);

        //Parse lines
        $string = preg_replace('/<hr(.*)>/Uis', "\r\n==================================\r\n", $string);

        //Parse breaklines
        $string = preg_replace('/<br(.*)>/Uis', "\r\n", $string);

        //Parse alineas
        $string = preg_replace('/<p(.*)>(.*)<\/p>/Uis', "\r\n$2\r\n", $string);

        //Parse table columns
        $string = preg_replace('/<tr>(.*)<\/tr>/Uis', "\r\n$1", $string);
        $string = preg_replace('/<td>(.*)<\/td>/Uis', "$1\t", $string);
        $string = preg_replace('/<th>(.*)<\/th>/Uis', "$1\t", $string);

        //Parse markedup text
        $string = preg_replace('/<b(.*)>(.*)<\/b>/Uis', '**$2**', $string);
        $string = preg_replace('/<strong(.*)>(.*)<\/strong>/Uis', '**$2**', $string);
        $string = preg_replace('/<i(.*)>(.*)<\/i>/Uis', '*$2*', $string);
        $string = preg_replace('/<u(.*)>(.*)<\/u>/Uis', '_$2_', $string);

        $string = preg_replace('/<h1(.*)>(.*)<\/h1>/Uis', "\r\n### $2 ###\r\n", $string);
        $string = preg_replace('/<h2(.*)>(.*)<\/h2>/Uis', "\r\n## $2 ##\r\n", $string);
        $string = preg_replace('/<h3(.*)>(.*)<\/h3>/Uis', "\r\n## $2 ##\r\n", $string);
        $string = preg_replace('/<h4(.*)>(.*)<\/h4>/Uis', "\r\n## $2 ##\r\n", $string);
        $string = preg_replace('/<h5(.*)>(.*)<\/h5>/Uis', "\r\n# $2 #\r\n", $string);
        $string = preg_replace('/<h6(.*)>(.*)<\/h6>/Uis', "\r\n# $2 #\r\n", $string);

        //Surround tables with newlines
        $string = preg_replace('/<table(.*)>(.*)<\/table>/Uis', "\r\n$2\r\n", $string);

        $string = html_entity_decode($string);

        $string = strip_tags($string);

        //Fix double whitespaces
        $string = preg_replace('/(  *)/', ' ', $string);

        //Newlines with a space behind it - don't need that. (except in some cases, in which you'll miss 1 whitespace.
        // Well, too bad for you. File a PR <3
        $string = preg_replace('/\r\n /', "\r\n", $string);
        $string = preg_replace('/ \r\n/', "\r\n", $string);

        //Remove tabs before newlines
        $string = preg_replace('/\t /', "\t", $string);
        $string = preg_replace('/\t \r\n/', "\r\n", $string);
        $string = preg_replace('/\t\r\n/', "\r\n", $string);

        $this->setText($string);
    }

    /**
     * @return string
     */
    private function getHtml()
    {
        return $this->html;
    }

    /**
     * @param $string
     * @return $this
     */
    private function setHtml($string)
    {
        $this->html = $string;
        return $this;
    }

    /**
     * @return string
     */
    private function getText()
    {
        return $this->text;
    }

    /**
     * @param $string
     * @return $this
     */
    private function setText($string)
    {
        $this->text = $string;
        return $this;
    }
}