<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use App\Exception\UserDoesNotHaveThisProductException;
use App\Repository\ProductRepository;
use App\Repository\RobokassaSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class RobokassaService
 * @package App\Service
 */
class RobokassaService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var RobokassaSettingsRepository */
    private RobokassaSettingsRepository $robokassaRepository;

    /** @var ProductRepository */
    private ProductRepository $productRepository;

    /**
     * RobokassaService constructor.
     * @param EntityManagerInterface      $entityManager
     * @param RobokassaSettingsRepository $robokassaRepository
     * @param ProductRepository           $productRepository
     */
    public function __construct(EntityManagerInterface $entityManager, RobokassaSettingsRepository $robokassaRepository, ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->robokassaRepository = $robokassaRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Сохранение настроек robokassa
     * @param User|UserInterface $user
     * @param Request            $request
     * @param FormInterface      $form
     * @return bool|void
     */
    public function save(User $user, Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->robokassaRepository->save($user, [
                'siteIdentity' => $form->getData()->getSiteIdentity(),
                'password1' => $form->getData()->getPassword1(),
                'password2' => $form->getData()->getPassword2(),
                'hashCalculationAlgorithm' => 'MD5',
                'invoiceId' => $form->getData()->getInvoiceId(),
            ]);
        }
    }

    /**
     * Генерация URL для кнопки
     * @param User|UserInterface $user
     * @param int                $productId
     * @return string
     * @throws UserDoesNotHaveThisProductException
     */
    public function generateUrl(User $user, int $productId)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($productId);
        $robokassaSettings = $user->getRobokassaSettings();
        $code = $this->generateHashCode($user, $product);

        $url = 'https://auth.robokassa.ru/Merchant/Index.aspx';
        $queryParams = [
            'MerchantLogin' => $robokassaSettings->getSiteIdentity(),
            'OutSum' => $product->getPrice(),
            'InvId' => $robokassaSettings->getInvoiceId(),
            'Description' => $product->getName(),
            'SignatureValue' => $code,
        ];

        return $url . '?' . http_build_query($queryParams);
    }

    /**
     * Генерация хеш-кода для кнопки
     * @param User|UserInterface $user
     * @param Product            $product
     * @return string
     * @throws UserDoesNotHaveThisProductException
     */
    public function generateHashCode(User $user, Product $product)
    {

        if (!$product || $product->getUser()->getId() !== $user->getId()) {
            throw new UserDoesNotHaveThisProductException();
        }

        $robokassaSettings = $user->getRobokassaSettings();

        $login = $robokassaSettings->getSiteIdentity();
        $summ = $product->getPrice();
        $invId = $robokassaSettings->getInvoiceId();
        $password1 = $robokassaSettings->getPassword1();

        return md5("{$login}:{$summ}:{$invId}:{$password1}");
    }
}
