# Emoji support for `league/commonmark`

[![Latest Version](http://img.shields.io/packagist/v/elgigi/commonmark-emoji.svg?style=flat-square)](https://github.com/ElGigi/CommonMarkEmoji/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/com/ElGigi/CommonMarkEmoji?style=flat-square)](https://travis-ci.com/ElGigi/CommonMarkEmoji)
[![Codacy Grade](https://img.shields.io/codacy/grade/98db5d34f26e481b8e63f9cc3a273033.svg?style=flat-square)](https://www.codacy.com/app/ElGigi/CommonMarkEmoji?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ElGigi/CommonMarkEmoji&amp;utm_campaign=Badge_Grade)
[![Total Downloads](https://img.shields.io/packagist/dt/elgigi/commonmark-emoji.svg?style=flat-square)](https://packagist.org/packages/elgigi/commonmark-emoji)

This extension adds UTF-8 emoji with Github tag for the `league/commonmark` PHP Markdown parsing engine, based on the CommonMark spec.

## Installation

You can install the client with [Composer](https://getcomposer.org/):

```bash
composer require elgigi/commonmark-emoji
```

## Usage

Extension can be added to any new Environment:

```php
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use ElGigi\CommonMarkEmoji\EmojiExtension;

// Obtain a pre-configured Environment with all the CommonMark parsers/renderers ready-to-go
$environment = Environment::createCommonMarkEnvironment();

// Add this extension
$environment->addExtension(new EmojiExtension());

// Instantiate the converter engine and start converting some Markdown!
$converter = new CommonMarkConverter($config, $environment);
echo $converter->convertToHtml('This extension is great :smile:!');
```