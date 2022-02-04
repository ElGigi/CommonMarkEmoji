<?php
/*
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2022 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

namespace ElGigi\CommonMarkEmoji\Tests;

use Berlioz\Http\Client\Client;
use ElGigi\CommonMarkEmoji\Emoji;
use PHPUnit\Framework\TestCase;

class EmojiCheck extends TestCase
{
    public function testEmojiList()
    {
        $client = new Client();
        $response = $client->get('https://api.github.com/emojis');
        $list = json_decode($response->getBody()->getContents(), true);

        $this->assertEmpty(array_diff(array_keys(Emoji::$codes), array_keys($list)));
    }
}