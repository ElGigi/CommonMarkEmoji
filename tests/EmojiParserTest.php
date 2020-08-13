<?php
/*
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2020 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

namespace ElGigi\CommonMarkEmoji\Tests;

use ElGigi\CommonMarkEmoji\Emoji;
use ElGigi\CommonMarkEmoji\EmojiParser;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use PHPUnit\Framework\TestCase;

class EmojiParserTest extends TestCase
{
    public function emojiProvider(): array
    {
        return
            array_map(
                function ($code, $utf8) {
                    return [$code, $utf8];
                },
                array_keys(Emoji::$codes),
                array_values(Emoji::$codes)
            );
    }

    /**
     * @param $actual
     * @param $expected
     * @dataProvider emojiProvider
     */
    public function testEmojiParser($actual, $expected)
    {
        $input = sprintf('Foo :%s: bar', $actual);
        $expected = sprintf("<p>Foo %s bar</p>", $expected);

        $emojiParser = new EmojiParser();

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addInlineParser($emojiParser);

        $converter = new CommonMarkConverter([], $environment);

        $this->assertEquals($expected, \rtrim($converter->convertToHtml($input)));
    }

    public function testEmojiParserWithBadText(): void
    {
        $input = 'Foo :smile test smile: bar';
        $expected = "<p>Foo :smile test smile: bar</p>";

        $emojiParser = new EmojiParser();

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addInlineParser($emojiParser);

        $converter = new CommonMarkConverter([], $environment);

        $this->assertEquals($expected, \rtrim($converter->convertToHtml($input)));
    }
}
