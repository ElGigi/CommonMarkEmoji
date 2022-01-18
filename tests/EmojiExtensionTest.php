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
use League\CommonMark\Environment\Environment;
use PHPUnit\Framework\TestCase;

class EmojiExtensionTest extends TestCase
{
    public function testRegister()
    {
        $environment = new Environment();
        $environment->addExtension($extension = new EmojiExtension());

        $this->assertContains($extension, $environment->getExtensions());
    }

    public function test()
    {
        $environment = new Environment();
        $environment->addInlineParser($parser = new EmojiParser());

        $this->assertContains($parser, $environment->getInlineParsers());
    }
}
