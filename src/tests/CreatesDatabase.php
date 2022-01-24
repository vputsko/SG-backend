<?php
declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\ToolsException;
use RuntimeException;

trait CreatesDatabase
{

    /**
     * Create database schema for the models or from the metadata mappings
     *
     * @param array $models
     * @return void
     * @throws ToolsException
     */
    public function createDatabaseSchema(array $models = []) : void
    {
        if ($this->em === null) {
            throw new RuntimeException('EntityManager not set.');
        }

        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);

        if ($models) {
            $classes = array();

            foreach ($models as $model) {
                $classes[] = $this->em->getClassMetadata($model);
            }
        } else {
            $classes = $this->em->getMetadataFactory()->getAllMetadata();
        }

        if ($classes) {
            $tool->createSchema($classes);
            printf("%s - Schema created.\n", static::class);
        } else {
            throw new RuntimeException('Metadata are absent.');
        }
    }

}
