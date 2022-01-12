<?php

declare(strict_types = 1);

namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Bernard\Doctrine\MessagesSchema;
use Doctrine\DBAL\Schema\Schema;

class CreateQueuesDoctrineSchemaCommand
{
    
    /** 
     * @var EntityManagerInterface 
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(InputInterface $input, OutputInterface $output): void
    {
        $schema = new Schema();
        MessagesSchema::create($schema);
        $sql = $schema->toSql($this->entityManager->getConnection()->getDatabasePlatform());

        foreach ($sql as $query) {
            $this->entityManager->getConnection()->executeStatement($query);
        }
    }
    
}

