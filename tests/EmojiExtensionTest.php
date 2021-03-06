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

use ElGigi\CommonMarkEmoji\EmojiParser;
use League\CommonMark\Environment;
use PHPUnit\Framework\TestCase;

class EmojiExtensionTest extends TestCase
{
    public function test()
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addInlineParser($parser = new EmojiParser());

        $this->assertContains($parser, $environment->getInlineParsersForCharacter(':'));
    }
}
