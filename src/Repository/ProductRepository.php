<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /** @var ContainerInterface */
    private ContainerInterface $container;

    /**
     * ProductRepository constructor.
     * @param ManagerRegistry $registry
     * @param ContainerInterface $container
     */
    public function __construct(ManagerRegistry $registry, ContainerInterface $container)
    {
        parent::__construct($registry, Product::class);
        $this->container = $container;
    }

    /**
     * Получить постранично список продуктов
     * @param User $user
     * @param int $page Номер странцы в списке
     * @param int|null $elementsOnPage
     * @return Product[]
     */
    public function getList(User $user, int $page, ?int $elementsOnPage = null): array
    {
        if (!$elementsOnPage) {
            $elementsOnPage = $this->container->getParameter('product')['max_result_on_page'];
        }

        $offset = ($page - 1) * $elementsOnPage;

        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select(['p'])
            ->from(Product::class, 'p')
            ->where('p.user = :userId')
            ->setParameter('userId', $user->getId(), Types::INTEGER)
            ->setFirstResult($offset)
            ->setMaxResults($elementsOnPage)
            ->getQuery();

        return $query->getResult();
    }
}
