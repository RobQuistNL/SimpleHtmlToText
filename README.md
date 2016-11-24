# SimpleHtmlToText
[![Build Status](https://travis-ci.org/RobQuistNL/SimpleHtmlToText.svg?branch=master)](https://travis-ci.org/RobQuistNL/SimpleHtmlToText)
[![Latest Stable Version](https://poser.pugx.org/robquistnl/simplehtmltotext/v/stable)](https://packagist.org/packages/robquistnl/simplehtmltotext)
[![Total Downloads](https://poser.pugx.org/robquistnl/simplehtmltotext/downloads)](https://packagist.org/packages/robquistnl/simplehtmltotext)
[![License](https://poser.pugx.org/robquistnl/simplehtmltotext/license)](https://packagist.org/packages/robquistnl/simplehtmltotext)

A simple PHP class for transforming HTML to plain text (e.g. for emails)

## Installation
Install using composer (``composer require robquistnl/simplehtmltotext``).

## Usage
```php
$myHtml = '<b>This is HTML</b><h1>Header</h1><br/><br/>Newlines';
echo (new Parser())->parseString($myHtml);
```
Returns: 
```
**This is HTML**
### Header ###


Newlines
```

## Supported tags
Currently only a few basic tags are supported, and no CSS is checked.

- ``br``
- ``hr``
- ``h1``, ``h2``, ``h3``, ``h4``, ``h5``, ``h6``
- ``table``, ``tr``, ``td``, ``th`` (Very basic support)
- ``b``, ``strong``, ``u``,  ``i``, ``em``
- ``a`` Simple support; ``<a href="http://example.org">Click here</a>`` becomes ``Click here (http://example.org)``
- ``img`` Simple support; ``<img src="http://example.org/image.jpg">`` becomes `` `` and  ``<img alt="title" src="http://example.org/image.jpg">`` becomes ``(title)``
- ``ul``, ``ol``, ``li``, ``dd``, ``dt``
