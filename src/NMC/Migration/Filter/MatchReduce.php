<?php
/**
 * NMC Migration
 *
 * @author      Josh Lockhart <josh@newmediacampaigns.com>
 * @copyright   2014 Josh Lockhart
 * @link        http://www.newmediacampaigns.com
 * @license     MIT Public License
 * @version     1.0.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace NMC\Migration\Filter;

/**
 * Match Filter
 *
 * The match filter matches an object's content against
 * a regular expression and reduces the object's content
 * to a specific index of the pattern matches.
 *
 * @package Migration
 * @author  Josh Lockhart <josh@newmediacampaigns.com>
 * @since   1.0
 */
class MatchReduce implements \NMC\Migration\FilterInterface
{
    /**
     * The regular expression pattern
     * @var string
     */
    protected $pattern;

    /**
     * If the pattern finds a match (or matches), return the pattern or sub-pattern
     * at this index. This is useful for matching against sub-patterns.
     * @var int
     */
    protected $matchIndex;

    /**
     * Constructor
     * @param string $pattern    The regular expression
     * @param int    $matchIndex Return the pattern match at this index in the match result set
     */
    public function __construct($pattern, $matchIndex = 0)
    {
        $this->pattern = $pattern;
        $this->matchIndex = $matchIndex;
    }

    /**
     * Apply filter
     * @param  \NMC\Migration\ObjectInterface $object
     * @throws \RuntimeException             If provided match index does not exist in match result set
     */
    public function apply(\NMC\Migration\ObjectInterface $object)
    {
        $result = preg_match($this->pattern, $object->getContent(), $matches);

        if (is_array($matches) === true && count($matches) > 0) {
            if (isset($matches[$this->matchIndex]) === false) {
                throw new \RuntimeException('Invalid match index `' . $this->matchIndex . '`. Index does not exist.');
            }
            $object->setContent($matches[$this->matchIndex]);
        }
    }
}
