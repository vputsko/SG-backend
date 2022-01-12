<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\RefreshesDatabase;
use App\Models\User;

class ExampleTest extends TestCase
{
    use RefreshesDatabase;

    public function testBasicTest()
    {
        $this->createDatabaseSchema();

        $user = new User();
        $user->setName('The first');
        $this->em->persist($user);
        $this->em->flush();

        self::assertIsNumeric($user->getId());
        self::assertTrue($this->em->contains($user));

        $user = $this->em->find('App\Models\User', 1);
        print($user->getId());
    }
}
