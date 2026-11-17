<?php
/*
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2021 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

declare(strict_types=1);

namespace ElGigi\CommonMarkEmoji;

use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

final class EmojiParser implements InlineParserInterface
{
    public function getMatchDefinition(): InlineParserMatch
    {
        // Only the opening colon is consumed by the lookahead, so overlapping candidates are all tried
        return InlineParserMatch::regex(':(?=[a-z0-9_+\-]+:)')->caseSensitive();
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();

        // The symbol must not be glued to a word or a number (e.g. "foo:bar:" or "10:30:45")
        $previousChar = $cursor->peek(-1);
        if ($previousChar !== null && preg_match('/^[\p{L}\p{N}]$/u', $previousChar)) {
            // peek() doesn't modify the cursor, so no need to restore state first
            return false;
        }

        // Save the cursor state in case we need to rewind and bail
        $previousState = $cursor->saveState();

        // Advance past the symbol to keep parsing simpler
        $cursor->advance();

        $identifier = $cursor->match('/^[a-z0-9_+\-]+(?=:)/');
        if ($identifier === null || !array_key_exists($identifier, Emoji::$codes)) {
            $cursor->restoreState($previousState);

            return false;
        }

        // Advance past the closing symbol
        $cursor->advance();

        $inlineContext->getContainer()->appendChild(new Text(Emoji::$codes[$identifier]));

        return true;
    }
}
