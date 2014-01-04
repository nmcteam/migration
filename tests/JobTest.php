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

class JobTest extends PHPUnit_Framework_TestCase
{
    protected function createSource()
    {
        return new \MockSource(array(
            new \NMC\Migration\Object('Foo')
        ));
    }

    protected function createFilter()
    {
        return new \NMC\Migration\Filter\Replace('#\d+#', function () {
            return '*';
        });
    }

    protected function createAction()
    {
        return new \NMC\Migration\Action\Imitate();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    public function testConstructor()
    {
        $source = $this->createSource();
        $job = new \NMC\Migration\Job($source);

        $this->assertAttributeSame($source, 'source', $job);
        $this->assertAttributeEmpty('filters', $job);
        $this->assertAttributeEmpty('actions', $job);
    }

    public function testAddFilter()
    {
        $filter = $this->createFilter();
        $job = new \NMC\Migration\Job($this->createSource());
        $job->addFilter($filter);

        $this->assertAttributeContains($filter, 'filters', $job);
    }

    public function testAddAction()
    {
        $action = $this->createAction();
        $job = new \NMC\Migration\Job($this->createSource());
        $job->addAction($action);

        $this->assertAttributeContains($action, 'actions', $job);
    }

    public function testRun()
    {
        // Source
        $object = new \NMC\Migration\Object('Foo');
        $source = new \MockSource(array($object));

        // Mock filter
        $filter = \Mockery::mock('\NMC\Migration\Filter\Replace[apply]', array('#\d+#', function () {
            return '*';
        }));
        $filter->shouldReceive('apply')->with($object)->once();

        // Mock action
        $action = \Mockery::mock('\NMC\Migration\Action\Imitate[run]');
        $action->shouldReceive('run')->with($object)->once();

        // Job
        $job = new \NMC\Migration\Job($source);
        $job->addFilter($filter);
        $job->addAction($action);
        $job->run();

        $this->assertFalse($job->hasErrors());
    }

    public function testRunWithErrors()
    {
        // Source
        $object = new \NMC\Migration\Object('Foo');
        $source = new \MockSource(array($object));

        // Mock filter
        $filter = \Mockery::mock('\NMC\Migration\Filter\Replace[apply]', array('#\d+#', function () {
            return '*';
        }));
        $filter->shouldReceive('apply')->with($object)->once()->andThrow(new \RuntimeException('Doom!'));

        // Mock action
        $action = \Mockery::mock('\NMC\Migration\Action\Imitate[run]');
        $action->shouldReceive('run')->with($object)->once();

        // Job
        $job = new \NMC\Migration\Job($source);
        $job->addFilter($filter);
        $job->addAction($action);
        $job->run();

        $this->assertTrue($job->hasErrors());
    }

    public function testGetErrors()
    {
        // Exception
        $exception = new \RuntimeException('Doom!');

        // Source
        $object = new \NMC\Migration\Object('Foo');
        $source = new \MockSource(array($object));

        // Mock filter
        $filter = \Mockery::mock('\NMC\Migration\Filter\Replace[apply]', array('#\d+#', function () {
            return '*';
        }));
        $filter->shouldReceive('apply')->with($object)->once()->andThrow($exception);

        // Mock action
        $action = \Mockery::mock('\NMC\Migration\Action\Imitate[run]');
        $action->shouldReceive('run')->with($object)->once();

        // Job
        $job = new \NMC\Migration\Job($source);
        $job->addFilter($filter);
        $job->addAction($action);
        $job->run();

        // Errors
        $errors = $job->getErrors();

        $this->assertCount(1, $errors);
        $this->assertSame($exception, $errors[0]['exception']);
        $this->assertSame($object, $errors[0]['object']);
    }
}
