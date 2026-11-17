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

use ElGigi\CommonMarkEmoji\EmojiExtension;
use ElGigi\CommonMarkEmoji\EmojiParser;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use PHPUnit\Framework\TestCase;

class EmojiExtensionTest extends TestCase
{
    public function testRegister(): void
    {
        $environment = new Environment();
        $environment->addExtension($extension = new EmojiExtension());

        $this->assertContains($extension, $environment->getExtensions());

        $parsers = iterator_to_array($environment->getInlineParsers(), false);
        $this->assertCount(
            1,
            array_filter($parsers, fn($parser): bool => $parser instanceof EmojiParser)
        );
    }

    public function testConvert(): void
    {
        $converter = new CommonMarkConverter();
        $converter->getEnvironment()->addExtension(new EmojiExtension());

        $this->assertEquals(
            '<p>This extension is great 😄!</p>',
            rtrim((string)$converter->convert('This extension is great :smile:!'))
        );
    }
}
