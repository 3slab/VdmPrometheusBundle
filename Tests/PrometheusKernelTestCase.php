<?php

namespace Vdm\Bundle\PrometheusBundle\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Vdm\Bundle\PrometheusBundle\Tests\Fixtures\AppBundle\AppBundle;
use Vdm\Bundle\PrometheusBundle\Tests\Kernel as KernelAlias;

abstract class PrometheusKernelTestCase extends KernelTestCase
{
    use WebTestAssertionsTrait;

    /**
     * Get PHP float regex
     *
     * @return string
     */
    public function getFloatRegex(): string
    {
        $lNum = '[0-9]+(_[0-9]+)*';
        $dNum = "([0-9]*(_[0-9]+)*[\.]{$lNum})|({$lNum}[\.][0-9]*(_[0-9]+)*)";
        return "(({$lNum})|({$dNum}))([eE][+-]?)?({$lNum})?";
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown(): void
    {
        if (static::$kernel instanceof KernelAlias) {
            (new Filesystem())->remove(static::$kernel->getCacheDir());
            (new Filesystem())->remove(static::$kernel->getLogDir());
            (new Filesystem())->remove(static::$kernel->getProjectDir() . DIRECTORY_SEPARATOR . 'var');
        }
        parent::tearDown();
        self::getClient(null);
    }

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        self::bootKernel();
        parent::setUp();
    }

    /**
     * Return the name of the app for the current test suite
     * @return string
     */
    abstract protected static function getAppName(): string;

    /**
     * {@inheritDoc}
     */
    protected static function createKernel(array $options = [])
    {
        static::$class = static::getKernelClass();
        $kernel = new static::$class('Tests/Fixtures/' . static::getAppName());
        $kernel->addBundles([new AppBundle()]);
        return $kernel;
    }

    /**
     * {@inheritDoc}
     */
    protected static function getKernelClass()
    {
        return KernelAlias::class;
    }

    /**
     * Creates a KernelBrowser.
     *
     * @param array $options An array of options to pass to the createKernel method
     * @param array $server  An array of server parameters
     *
     * @return KernelBrowser A KernelBrowser instance
     */
    protected static function createClient(array $options = [], array $server = [])
    {
        try {
            $client = static::$kernel->getContainer()->get('test.client');
        } catch (ServiceNotFoundException $e) {
            if (class_exists(KernelBrowser::class)) {
                throw new \LogicException(
                    'You cannot create the client used in functional tests if the "framework.test" 
                    config is not set to true.'
                );
            }
            throw new \LogicException(
                'You cannot create the client used in functional tests if the BrowserKit component 
                is not available. Try running "composer require symfony/browser-kit".'
            );
        }

        $client->setServerParameters($server);

        return self::getClient($client);
    }
}
