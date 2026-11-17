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

use ElGigi\CommonMarkEmoji\Emoji;
use PHPUnit\Framework\TestCase;

/**
 * Compares the emoji list with the GitHub API; run bin/update-emoji.php to fix differences.
 */
class EmojiCheck extends TestCase
{
    public function testEmojiList(): void
    {
        $context = stream_context_create(['http' => ['header' => "User-Agent: elgigi/commonmark-emoji\r\n"]]);
        $contents = file_get_contents('https://api.github.com/emojis', false, $context);
        $this->assertIsString($contents, 'Unable to fetch the GitHub emoji list');

        $list = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        // Keep only Unicode emoji, GitHub also provides custom images (:octocat:, :shipit:...)
        $expected = [];
        foreach ($list as $code => $url) {
            if (preg_match('#/unicode/([0-9a-f\-]+)\.png#', $url, $matches)) {
                $expected[(string)$code] = $matches[1];
            }
        }

        $actual = [];
        foreach (Emoji::$codes as $code => $utf8) {
            // GitHub image names omit zero width joiners and variation selectors
            $codePoints = array_diff(array_map('mb_ord', mb_str_split($utf8)), [0x200d, 0xfe0f]);
            $actual[(string)$code] = implode('-', array_map(fn(int $codePoint) => sprintf('%04x', $codePoint), $codePoints));
        }

        ksort($expected, SORT_STRING);
        ksort($actual, SORT_STRING);

        $this->assertEquals($expected, $actual);
    }
}
