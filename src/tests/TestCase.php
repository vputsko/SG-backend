<?php
declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesDatabase;

    /**
     * The Doctrine EntityManager
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp() : void
    {
        if (! $this->em) {
            $this->em = $this->createEntityManager();
        }
        $this->setUpTraits();
    }

    /**
     * Create the Doctrine EntityManager
     *
     * @return EntityManager
     */
    public function createEntityManager() : EntityManager
    {
        return require __DIR__."/../bootstrap/bootstrap_doctrine.php";
    }

    /**
     * Boot the testing helper traits.
     *
     * @return void
     */
    protected function setUpTraits() : void
    {
        $uses = array_flip($this->class_uses_recursive(static::class));

        if (isset($uses[RefreshesDatabase::class])) {
            $this->refreshDatabase();
        }
    }

    /**
     * Returns all traits used by a class, its subclasses and trait of their traits.
     *
     * @param  object|string  $class
     * @return array
     */
    private function class_uses_recursive(string $class) : array
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];

        foreach (array_merge([$class => $class], class_parents($class)) as $class) {
            $results += $this->trait_uses_recursive($class);
        }

        return array_unique($results);
    }

    /**
     * Returns all traits used by a trait and its traits.
     *
     * @param  string  $trait
     * @return array
     */
    private function trait_uses_recursive(string $trait) : array
    {
        $traits = class_uses($trait);

        foreach ($traits as $trait) {
            $traits += $this->trait_uses_recursive($trait);
        }

        return $traits;
    }
}
