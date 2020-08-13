<?php
/*
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2020 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

declare(strict_types=1);

namespace ElGigi\CommonMarkEmoji;

use League\CommonMark\Inline\Element\Text;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;

final class EmojiParser implements InlineParserInterface
{
    public function getCharacters(): array
    {
        return [':'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();

        // The symbol must not have any other characters immediately prior
        $previousChar = $cursor->peek(-1);
        if ($previousChar !== null && $previousChar !== ' ') {
            // peek() doesn't modify the cursor, so no need to restore state first
            return false;
        }

        // Save the cursor state in case we need to rewind and bail
        $previousState = $cursor->saveState();

        // Advance past the symbol to keep parsing simpler
        $cursor->advance();

        // Parse the emoji match value
        $identifier = $cursor->match("/^[\w\-\+]+\:/i");
        if ($identifier === null) {
            // Regex failed to match; this isn't a valid emoji
            $cursor->restoreState($previousState);

            return false;
        }

        $identifier = substr($identifier, 0, -1);

        if (!array_key_exists($identifier, Emoji::$codes)) {
            $cursor->restoreState($previousState);

            return false;
        }

        $inlineContext->getContainer()->appendChild(new Text(Emoji::$codes[$identifier]));

        return true;
    }
}
