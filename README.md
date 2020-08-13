# Emoji support for `league/commonmark`

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