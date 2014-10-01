<?php

use AspectMock\Test;
use Hamcrest\MatcherAssert;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use Way\Tests\ControllerHelpers;
    use Way\Tests\ModelHelpers;

    /**
     * Collects assertions performed by Hamcrest matchers during the test.
     *
     * @throws Exception
     */
    public function runBare()
    {
        MatcherAssert::resetCount();

        try {
            parent::runBare();
        }
        catch (\Exception $exception) {
            // rethrown below
        }

        $this->addToAssertionCount(MatcherAssert::getCount());

        if (isset($exception)) {
            throw $exception;
        }

    }

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__.'/../../bootstrap/start.php';
    }

    public function setUp()
    {
        parent::setUp();

        $this->appPath = app_path();

        $this->collection = Mockery::mock('PullAutomaticallyGalleries\Database\Eloquent\Collection')->shouldDeferMissing();

        $this->setUpDatabase();
    }

    public function setUpDatabase()
    {
        File::copy(
            $this->appPath.'\database\testing\dump.sqlite',
            $this->appPath.'\database\testing\testing.sqlite'
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();

        Test::clean();

        $this->dropDatabase();
    }

    public function dropDatabase()
    {
        File::delete($this->appPath.'\database\testing\testing.sqlite');
    }

}
