<?php declare(strict_types=1);


namespace App\Service;


use App\Entity\Product;
use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TransactionService
 * @package App\Service
 */
class TransactionService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var TransactionRepository */
    private TransactionRepository $transactionRepository;

    /**
     * TransactionService constructor.
     * @param EntityManagerInterface $entityManager
     * @param TransactionRepository  $transactionRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TransactionRepository $transactionRepository)
    {
        $this->entityManager = $entityManager;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Добавление товара
     * @param Product       $product
     * @param Request       $request
     * @param FormInterface $form
     * @return bool|void
     */
    public function add(Product $product, Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Transaction $transaction */
            $transaction = $form->getData();
            $transaction->setProduct($product);

            try {
                $this->entityManager->persist($transaction);
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
     * @return Transaction[]
     */
    public function getList(User $user, int $page, ?int $elementOnPage = null)
    {
        return $this->transactionRepository->getList($user, $page, $elementOnPage);
    }

    /**
     * Получить количество товаров
     * @param User|UserInterface $user
     * @return int
     */
    public function getTotal(User $user)
    {
        return $this->transactionRepository->count([
            'user' => $user,
        ]);
    }
}