<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * Class UserRepository
 * @package App\Repository
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $this->getEntityManager();
    }

    /**
     * @param User $user
     * @param array $userData
     * @return bool
     */
    public function save(User $user, array $userData): bool
    {
        try {
            $user->setEmail($userData['email']);
            $user->setFirstName($userData['first_name']);
            $user->setLastName($userData['last_name']);
            $user->setLocation($userData['location']);
            $user->setWebsite($userData['website']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param User $user
     * @param string $encodePassword
     * @return bool
     */
    public function changePassword(User $user, string $encodePassword)
    {
        try {
            $user->setPassword($encodePassword);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
