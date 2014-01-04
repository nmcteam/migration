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
namespace NMC\Migration;

/**
 * Object
 *
 * This class represents a single object from a given data
 * source. It should, at the very least, implement the
 * `\NMC\Migration\ObjectInterface` interface.
 *
 * @package Migration
 * @author  Josh Lockhart <josh@newmediacampaigns.com>
 * @since   1.0
 */
class Object implements \IteratorAggregate, \ArrayAccess, \NMC\Migration\ObjectInterface
{
    /**
     * The object's raw content
     * @var string
     */
    protected $content;

    /**
     * The object's properties (accessible via array access interface)
     * @var array
     */
    protected $properties;

    /**
     * Construct
     * @param string $content
     * @param array  $properties
     */
    public function __construct($content = '', array $properties = array())
    {
        $this->content = (string)$content;
        $this->properties = $properties;
    }

    /**
     * Get object content
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set object content
     * @param string $text
     */
    public function setContent($text)
    {
        $this->content = (string)$text;
    }

    /**
     * Set offset
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset) === true) {
            $this->properties[] = $value;
        } else {
            $this->properties[$offset] = $value;
        }
    }

    /**
     * Offset exists?
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->properties[$offset]) === true;
    }

    /**
     * Unset offset
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->properties[$offset]);
    }

    /**
     * Get offset
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->properties[$offset]) === true ? $this->properties[$offset] : null;
    }

    /**
     * Get iterator
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->properties);
    }
}
