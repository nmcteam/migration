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
 * Source Interface
 *
 * A source should provide an iterator interface to a set
 * of `\NMC\Migration\ObjectInterface` objects that represent
 * data from a given data source.
 *
 * For example, you could build a CSV source that provides
 * `\NMC\Migration\ObjectInterface` objects for each CSV file row.
 * You could also provide a PDO source that provides
 * `\NMC\Migration\ObjectInterface` objects for each row
 * in a query result.
 *
 * @package Migration
 * @author  Josh Lockhart <josh@newmediacampaigns.com>
 * @since   1.0
 */
interface SourceInterface extends \Iterator
{
    /**
     * Fetch current item
     * @return \NMC\Migration\ObjectInterface
     */
    public function current();
}
