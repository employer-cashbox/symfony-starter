<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    /** @var ContainerInterface */
    private ContainerInterface $container;

    /**
     * TransactionRepository constructor.
     * @param ManagerRegistry    $registry
     * @param ContainerInterface $container
     */
    public function __construct(ManagerRegistry $registry, ContainerInterface $container)
    {
        parent::__construct($registry, Transaction::class);
        $this->container = $container;
    }

    /**
     * Получить постранично список транзакций
     * @param User     $user
     * @param int      $page Номер странцы в списке
     * @param int|null $elementsOnPage
     * @return Transaction[]
     */
    public function getList(User $user, int $page, ?int $elementsOnPage = null): array
    {
        if (!$elementsOnPage) {
            $elementsOnPage = $this->container->getParameter('transaction')['max_result_on_page'];
        }

        $offset = ($page - 1) * $elementsOnPage;

        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select(['t'])
            ->from(Transaction::class, 't')
            ->where('t.user = :userId')
            ->setParameter('userId', $user->getId(), Types::INTEGER)
            ->setFirstResult($offset)
            ->setMaxResults($elementsOnPage)
            ->getQuery();

        return $query->getResult();
    }
}
