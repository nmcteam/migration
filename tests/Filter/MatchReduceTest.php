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

class MatchReduceTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $pattern = '#\d+#';
        $index = 1;
        $filter = new \NMC\Migration\Filter\MatchReduce($pattern, $index);

        $this->assertAttributeEquals($pattern, 'pattern', $filter);
        $this->assertAttributeSame($index, 'matchIndex', $filter);
    }

    public function testApply()
    {
        $object = new \NMC\Migration\Object('<body onload="foo();">Foo</body>');
        $filter = new \NMC\Migration\Filter\MatchReduce('#<body[^>]+>(.+)</body>#', 1);
        $filter->apply($object);

        $this->assertEquals('Foo', $object->getContent());
    }

    public function testApplyWithInvalidIndex()
    {
        $this->setExpectedException('\RuntimeException');

        $object = new \NMC\Migration\Object('<body onload="foo();">Foo</body>');
        $filter = new \NMC\Migration\Filter\MatchReduce('#<body[^>]+>(.+)</body>#', 3);
        $filter->apply($object);
    }
}
