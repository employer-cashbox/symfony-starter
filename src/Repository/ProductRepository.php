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
     * @param ManagerRegistry    $registry
     * @param ContainerInterface $container
     */
    public function __construct(ManagerRegistry $registry, ContainerInterface $container)
    {
        parent::__construct($registry, Product::class);
        $this->container = $container;
    }

    /**
     * Получить постранично список товаров
     * @param User     $user           Пользователь которому принадлежит товар
     * @param int      $page           Номер странцы в списке
     * @param int|null $elementsOnPage Количество товаров на странице
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
            ->andWhere('p.isDeleted = :isDeleted')
            ->setParameter('userId', $user->getId(), Types::INTEGER)
            ->setParameter('isDeleted', false, Types::BOOLEAN)
            ->setFirstResult($offset)
            ->setMaxResults($elementsOnPage)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Запрос удаления товара по его ID и пользователю
     * @param User $user      Пользователь которому принадлежит товар
     * @param int  $productId ID товара
     * @return int Количество удаленных товаров
     */
    public function delete(User $user, int $productId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->update(Product::class, 'p')
            ->set('p.isDeleted', true)
            ->where('p.user = :userId')
            ->andWhere('p.id = :productId')
            ->setParameter('userId', $user->getId(), Types::INTEGER)
            ->setParameter('productId', $productId, Types::INTEGER)
            ->getQuery()
            ->getResult();
    }
}
