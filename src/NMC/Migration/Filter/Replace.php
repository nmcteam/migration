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
 * Replace Filter
 *
 * The replace filter replaces object content
 * that matches a provided regular expression with
 * a given replacement value.
 *
 * @package Migration
 * @author  Josh Lockhart <josh@newmediacampaigns.com>
 * @since   1.0
 */
class Replace implements \NMC\Migration\FilterInterface
{
    /**
     * The regular expression pattern
     * @var string
     */
    protected $pattern;

    /**
     * If the pattern finds a match (or matches), replace the matches
     * with values generated by this callback.
     * @var Closure
     */
    protected $callback;

    /**
     * Constructor
     * @param string  $pattern   The regular expression
     * @param Closure $callback  The callback used to generate replacement values
     */
    public function __construct($pattern, \Closure $callback)
    {
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    /**
     * Apply filter
     * @param  \NMC\Migration\ObjectInterface $object
     * @throws \RuntimeException              If error during replacement
     */
    public function apply(\NMC\Migration\ObjectInterface $object)
    {
        $content = preg_replace_callback($this->pattern, $this->callback, $object->getContent());
        if ($content === null) {
            throw new \RuntimeException('Error while replacing values');
        }

        $object->setContent($content);
    }
}
