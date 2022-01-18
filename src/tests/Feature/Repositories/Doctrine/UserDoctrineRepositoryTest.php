<?php

namespace Tests\Feature\Repositories\Doctrine;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Tests\RefreshesDatabase;
use Tests\TestCase;

class UserDoctrineRepositoryTest extends TestCase
{

    use RefreshesDatabase;

    /**
     * @var UserRepositoryInterface 
     */
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->em->getRepository(User::class);
    }

    public function testGetUser(): void
    {
        $this->createDatabaseSchema();

        $user = new User;
        $user->setName('The first');
        $user->setEmail('first@exapmle.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($user);
        $this->em->flush();

        $userTest = $this->userRepository->getUser(1);

        self::assertIsNumeric($userTest->getId());
        self::assertSame($user->getId(), $userTest->getId());
        //self::assertTrue($this->em->contains($user));
        //$user = $this->em->find('App\Models\User', 1);
    }


    public function testNotFoundGetUser(): void
    {
        $this->createDatabaseSchema();

        $this->expectException(EntityNotFoundException::class);

        $user = new User;
        $user->setName('The first');
        $user->setEmail('first@exapmle.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($user);
        $this->em->flush();

        $userTest = $this->userRepository->getUser(2);
    }

    public function testGetUsers(): void
    {
        $this->createDatabaseSchema();

        $userOne = new User;
        $userOne->setName('The first');
        $userOne->setEmail('first@exapmle.com');
        $userOne->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($userOne);

        $userSecond = new User;
        $userSecond->setName('The second');
        $userSecond->setEmail('second@exapmle.com');
        $userSecond->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($userSecond);

        $this->em->flush();

        $usersTest = $this->userRepository->getUsers();

        self::assertCount(2, $usersTest);
    }

    public function testNotFoundGetUsersBy(): void
    {
        $this->createDatabaseSchema();

        $this->expectException(EntityNotFoundException::class);

        $user = new User;
        $user->setName('The first');
        $user->setEmail('first@exapmle.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($user);
        $this->em->flush();

        $criteria = [ 'email' => 'second@exapmle.com'];
        $userTest = $this->userRepository->getUsersBy($criteria);
    }

}
