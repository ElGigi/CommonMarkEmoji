<?php
/*
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2021 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

namespace ElGigi\CommonMarkEmoji\Tests;

use ElGigi\CommonMarkEmoji\Emoji;
use ElGigi\CommonMarkEmoji\EmojiParser;
use League\CommonMark\CommonMarkConverter;
use PHPUnit\Framework\TestCase;

use function rtrim;

class EmojiParserTest extends TestCase
{
    private function convert(string $markdown): string
    {
        $converter = new CommonMarkConverter([]);
        $converter->getEnvironment()->addInlineParser(new EmojiParser());

        return rtrim((string)$converter->convert($markdown));
    }

    public function emojiProvider(): array
    {
        return
            array_map(
                function ($code, $utf8) {
                    return [(string)$code, $utf8];
                },
                array_keys(Emoji::$codes),
                array_values(Emoji::$codes)
            );
    }

    /**
     * @dataProvider emojiProvider
     */
    public function testEmojiParser(string $actual, string $expected): void
    {
        $this->assertEquals(
            sprintf('<p>Foo %s bar</p>', $expected),
            $this->convert(sprintf('Foo :%s: bar', $actual))
        );
    }

    public function contextProvider(): array
    {
        return [
            'start of text' => [':smile: foo', '<p>😄 foo</p>'],
            'end of text' => ['foo :smile:', '<p>foo 😄</p>'],
            'alone' => [':smile:', '<p>😄</p>'],
            'after soft break' => ["foo\n:smile: bar", "<p>foo\n😄 bar</p>"],
            'after hard break' => ["foo  \n:smile: bar", "<p>foo<br />\n😄 bar</p>"],
            'after tab' => ["foo\t:smile:", "<p>foo\t😄</p>"],
            'in parentheses' => ['foo (:smile:)', '<p>foo (😄)</p>'],
            'after punctuation' => ['foo,:smile:', '<p>foo,😄</p>'],
            'followed by punctuation' => ['foo :smile:!', '<p>foo 😄!</p>'],
            'in strong' => ['**:smile:**', '<p><strong>😄</strong></p>'],
            'in emphasis' => ['_:smile:_', '<p><em>😄</em></p>'],
            'in link text' => ['[:smile:](https://example.com)', '<p><a href="https://example.com">😄</a></p>'],
            'in heading' => ['# :smile:', '<h1>😄</h1>'],
            'in list item' => ['- :smile:', "<ul>\n<li>😄</li>\n</ul>"],
            'adjacent' => [':smile::smile:', '<p>😄😄</p>'],
            'after unknown code' => [':foo::smile:', '<p>:foo:😄</p>'],
            'with numeric code' => ['foo :100:', '<p>foo 💯</p>'],
            'with plus sign' => ['foo :+1:', '<p>foo 👍</p>'],
            'with minus sign' => ['foo :-1:', '<p>foo 👎</p>'],
            'with multibyte text' => ['été :smile:', '<p>été 😄</p>'],
            'zwj sequence' => [':family_man_woman_boy:', "<p>\u{1f468}\u{200d}\u{1f469}\u{200d}\u{1f466}</p>"],
            'emoji presentation' => [':heart:', "<p>\u{2764}\u{fe0f}</p>"],
        ];
    }

    /**
     * @dataProvider contextProvider
     */
    public function testEmojiParserContext(string $markdown, string $expected): void
    {
        $this->assertEquals($expected, $this->convert($markdown));
    }

    public function ignoredProvider(): array
    {
        return [
            'spaces inside' => ['Foo :smile test smile: bar'],
            'unknown code' => ['Foo :not_an_emoji: bar'],
            'uppercase code' => ['Foo :SMILE: bar'],
            'glued to a word' => ['foo:smile:'],
            'glued to a multibyte word' => ['été:smile:'],
            'time' => ['at 10:30:45 today'],
            'not closed' => ['Foo :smile bar'],
            'empty' => ['Foo :: bar'],
            'inline code' => ['`:smile:`'],
        ];
    }

    /**
     * @dataProvider ignoredProvider
     */
    public function testEmojiParserIgnored(string $markdown): void
    {
        $this->assertStringNotContainsString('😄', $this->convert($markdown));
        $this->assertStringContainsString(
            htmlspecialchars(trim($markdown, '`'), ENT_NOQUOTES),
            $this->convert($markdown)
        );
    }
}
