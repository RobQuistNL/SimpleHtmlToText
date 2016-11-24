<?php
namespace SimpleHtmlToText;

class Parser {
    /** @var  string */
    private $html;

    /** @var  string */
    private $text;

    private $parseRules = [
        '/\s+/' => ' ', //Remove HTML's whitespaces
        '/<(img)\b[^>]*alt=\"([^>"]+)\"[^>]*>/Uis' => '($2)', //Parse image tags with alt
        '/<(img)\b[^>][^>]*>/Uis' => '', // Remove image tags without alt
        '/<a(.*)href=[\'"](.*)[\'"]>(.*)<\/a>/Uis' => '$3 ($2)', //Parse links
        '/<hr(.*)>/Uis' => "\n==================================\n", //Parse lines
        '/<br(.*)>/Uis' => "\n", //Parse breaklines
        '/<(.*)br>/Uis' => "\n", //Parse broken breaklines
        '/<p(.*)>(.*)<\/p>/Uis' => "\n$2\n", //Parse alineas

        //Lists
        '/(<ul\b[^>]*>|<\/ul>)/i' => "\n\n",
        '/(<ol\b[^>]*>|<\/ol>)/i' => "\n\n",
        '/(<dl\b[^>]*>|<\/dl>)/i' => "\n\n",

        '/<li\b[^>]*>(.*?)<\/li>/i' => "\t* $1\n",
        '/<dd\b[^>]*>(.*?)<\/dd>/i' => "$1\n",
        '/<dt\b[^>]*>(.*?)<\/dt>/i' => "\t* $1",
        '/<li\b[^>]*>/i' => "\n\t* ",

        //Parse table columns
        '/<tr>(.*)<\/tr>/Uis' => "\n$1",
        '/<td>(.*)<\/td>/Uis' => "$1\t",
        '/<th>(.*)<\/th>/Uis' => "$1\t",
        //Parse markedup text
        '/<em\b[^>]*>(.*?)<\/em>/i' => "$2",
        '/<b(.*)>(.*)<\/b>/Uis' => '**$2**',
        '/<strong(.*)>(.*)<\/strong>/Uis' => '**$2**',
        '/<i>(.*)<\/i>/Uis' => '*$2*',
        '/<u>(.*)<\/u>/Uis' => '_$2_',
        //Headers
        '/<h1(.*)>(.*)<\/h1>/Uis' => "\n### $2 ###\n",
        '/<h2(.*)>(.*)<\/h2>/Uis' => "\n## $2 ##\n",
        '/<h3(.*)>(.*)<\/h3>/Uis' => "\n## $2 ##\n",
        '/<h4(.*)>(.*)<\/h4>/Uis' => "\n## $2 ##\n",
        '/<h5(.*)>(.*)<\/h5>/Uis' => "\n# $2 #\n",
        '/<h6(.*)>(.*)<\/h6>/Uis' => "\n# $2 #\n",
        //Surround tables with newlines
        '/<table(.*)>(.*)<\/table>/Uis' => "\n$2\n",
    ];

    /**
     * @param $rule
     * @param $value
     */
    public function setParseRule($rule, $value) {
        $this->parseRules[$rule] = $value;
    }

    /**
     * @param $rule
     */
    public function removeParseRule($rule) {
        if (array_key_exists($rule, $this->parseRules)) {
            unset($this->parseRules[$rule]);
        }
    }

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

        foreach ($this->parseRules as $rule => $output) {
            $string = preg_replace($rule, $output, $string);
        }

        $string = html_entity_decode($string);

        //Strip remaining tags
        $string = strip_tags($string);

        //Fix double whitespaces
        $string = preg_replace('/(  *)/', ' ', $string);

        //Newlines with a space behind it - don't need that. (except in some cases, in which you'll miss 1 whitespace.
        // Well, too bad for you. File a PR <3
        $string = preg_replace('/\n /', "\n", $string);
        $string = preg_replace('/ \n/', "\n", $string);

        //Remove tabs before newlines
        $string = preg_replace('/\t /', "\t", $string);
        $string = preg_replace('/\t \n/', "\n", $string);
        $string = preg_replace('/\t\n/', "\n", $string);

        //Replace all \n with \r\n because some clients prefer that
        $string = preg_replace('/\n/', "\r\n", $string);

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