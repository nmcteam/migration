# Migration

This mini-framework is designed to migrate content from a source to a destination
using a source (that provides iterable content), filters (to manipulate the content),
and actions (to do something with the content).

## How to install

Use [Composer][composer] to install this framework. In your `composer.json` file:

    {
        "require": {
            "nmcteam/migration": "~0.1.0"
        }
    }

Then run `composer install`.

## Getting Started

In this example, we create a job that echoes the HTML content from a set of URLs.
Our "source" is a custom iterator that returns instances of `\NMC\Migration\Object`.
We use a filter to reduce the source HTML to only the markup within the `<body>` tags.
We use an "action" to echo the HTML content.

    <?php
    // Source
    $source = new \NMC\Migration\Source\UrlArray(['http://www.apple.com', 'http://www.php.net']);

    // Job
    $job = new \NMC\Migration\Job($source);

    // Filter
    $filter = new \NMC\Migration\Filter\MatchReduce('#<body[^>]+>(.+)</body>#', 1);
    $job->addFilter($filter);

    // Action
    $action = new \NMC\Migration\Action\Echo();
    $job->addAction($action);

    // Run job
    $job->run();

### What is a job?

A "job" is a collection of a source, filters, and actions. It will iterate the source, and apply its filters
and actions to each object provided by the source.

### What is a source?

A "source" is an iterator whose `current()` method returns an instance of `\NMC\Migration\ObjectInterface`.
What the source iterates, and how, is entirely up to you.

### What is a filter?

A "filter" is an object that receives an instance of `\NMC\Migration\ObjectInterface` and manipulates
the object's content. Changes to the object are performed by reference.

### What is an action?

An "action" is an object that receives an instance of `\NMC\Migration\ObjectInterface` and does something
with the object. An action could be as simple as `echo`, or it could use the object to create new database
objects, insert new pages into a CMS, or generate new data in a variety of formats (e.g. CSV or JSON).

## How to Contribute

1. Fork this repository.
2. Create a separate branch for each new feature.
3. Submit a pull request from each feature branch.

All pull requests must adhere to the [PSR-2][psr2] code styleguide. Each pull request must also be accompanied
by passing PHPUnit tests.

## Author

Copyright &copy; 2014 [New Media Campaigns][nmc].

## License

MIT Public License.

[composer]: http://getcomposer.org/
[psr2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[nmc]: http://www.newmediacampaigns.com
