<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
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

    /**
     * RobokassaService constructor.
     * @param EntityManagerInterface      $entityManager
     * @param RobokassaSettingsRepository $robokassaRepository
     */
    public function __construct(EntityManagerInterface $entityManager, RobokassaSettingsRepository $robokassaRepository)
    {
        $this->entityManager = $entityManager;
        $this->robokassaRepository = $robokassaRepository;
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
}
