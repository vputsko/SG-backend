<?php
declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\Tools\SchemaTool;
use RuntimeException;

trait RefreshesDatabase
{

    /**
     * Delete all schemas from the database
     *
     * @return void
     */
    public function refreshDatabase() : void
    {
        if ($this->em === null) {
            throw new RuntimeException('EntityManager not set.');
        }

        $metaData = $this->em->getMetadataFactory()->getAllMetadata();

        $tool = new SchemaTool($this->em);
        $tool->dropSchema($metaData);
        printf("%s - Database refreshed.\n", static::class);
    }

}
