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
 * Job
 *
 * A job accepts a source, filters, and actions. It will
 * apply the filters and actions to each object provided by the
 * source. Errors received while applying a filter or action
 * are collected and available with `getErrors()`.
 *
 * @package Migration
 * @author  Josh Lockhart <josh@newmediacampaigns.com>
 * @since   1.0
 */
class Job
{
    /**
     * The job source that provides iterable objects
     * @var \NMC\Migration\SourceInterface
     */
    protected $source;

    /**
     * Errors during last run
     * @var array[
     *          'exception' => \Exception,
     *          'object'    => \NMC\Migration\ObjectInterface
     *      ]
     */
    protected $errors;

    /**
     * List of filters to apply to each object
     * @var array[\NMC\Migration\FilterInterface]
     */
    protected $filters;

    /**
     * List of actions to apply to each object
     * @var array[\NMC\Migration\ActionInterface]
     */
    protected $actions;

    /**
     * Constructor
     * @var \NMC\Migration\SourceInterface
     */
    public function __construct(\NMC\Migration\SourceInterface $source)
    {
        $this->source = $source;
        $this->filters = array();
        $this->actions = array();
    }

    /**
     * Add filter to job
     * @param \NMC\Migration\FilterInterface $filter
     */
    public function addFilter(\NMC\Migration\FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Add action to job
     * @param \NMC\Migration\ActionInterface $action
     */
    public function addAction(\NMC\Migration\ActionInterface $action)
    {
        $this->actions[] = $action;
    }

    /**
     * Were there errors in the latest run?
     * @return bool
     */
    public function hasErrors()
    {
        return empty($this->errors) === false;
    }

    /**
     * Get errors from latest run
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Run job
     * @return bool              True if no errors
     * @throws \RuntimeException If source returns object with incorrect interface
     */
    public function run()
    {
        // Reset errors
        $this->errors = array();

        // Rewind source
        $this->source->rewind();

        // Iterate source
        foreach ($this->source as $object) {
            if ($object instanceof \NMC\Migration\ObjectInterface === false) {
                throw new \RuntimeException('Source must return instance of \NMC\Migration\ObjectInterface');
            }
            $this->applyFilters($object);
            $this->runActions($object);
        }

        return $this->hasErrors() === false;
    }

    /**
     * Apply filters to object
     * @param \NMC\Migration\ObjectInterface $object
     */
    protected function applyFilters(\NMC\Migration\ObjectInterface $object)
    {
        foreach ($this->filters as $filter) {
            try {
                $filter->apply($object);
            } catch (\Exception $e) {
                $this->errors[] = array(
                    'exception' => $e,
                    'object' => $object
                );
            }
        }
    }

    /**
     * Run actions on object
     * @param \NMC\Migration\ObjectInterface $object
     */
    protected function runActions(\NMC\Migration\ObjectInterface $object)
    {
        foreach ($this->actions as $action) {
            try {
                $action->run($object);
            } catch (\Exception $e) {
                $this->errors[] = array(
                    'exception' => $e,
                    'object' => $object
                );
            }
        }
    }
}
