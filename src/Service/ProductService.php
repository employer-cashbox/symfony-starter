<?php declare(strict_types=1);


namespace App\Service;


use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ProductService
 * @package App\Service
 */
class ProductService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var ProductRepository */
    private ProductRepository $productRepository;

    /**
     * ProductService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository      $productRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * Добавление товара
     * @param UserInterface $user
     * @param Request       $request
     * @param FormInterface $form
     * @return bool|void
     */
    public function add(UserInterface $user, Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $product->setUser($user);

            try {
                $this->entityManager->persist($product);
                $this->entityManager->flush();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    }

    /**
     * Получить список товаров
     * @param User|UserInterface $user
     * @param int|null           $page
     * @param int|null           $elementOnPage
     * @return Product[]
     */
    public function getList(User $user, int $page, ?int $elementOnPage = null)
    {
        return $this->productRepository->getList($user, $page, $elementOnPage);
    }

    /**
     * Получить количество товаров
     * @param User|UserInterface $user
     * @return int
     */
    public function getTotal(User $user)
    {
        return $this->productRepository->count([
            'user' => $user,
        ]);
    }

    /**
     * Удалить товар
     * @param User|UserInterface $user
     * @param int                $productId
     * @return int Количество удленных товаров
     */
    public function delete(User $user, int $productId)
    {
        return $this->productRepository->delete($user, $productId);
    }
}