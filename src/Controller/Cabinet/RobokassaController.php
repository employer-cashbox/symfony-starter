<?php declare(strict_types=1);


namespace App\Controller\Cabinet;

use App\Entity\User;
use App\Form\RobokassaType;
use App\Service\RobokassaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RobokassaController
 * @package App\Controller\Cabinet
 */
class RobokassaController extends AbstractController
{
    /** @var RobokassaService */
    private RobokassaService $robokassaService;

    /**
     * RobokassaController constructor.
     * @param RobokassaService $robokassaService
     */
    public function __construct(RobokassaService $robokassaService)
    {
        $this->robokassaService = $robokassaService;
    }

    /**
     * @Route("/cabinet/robokassa/settings", methods={"GET", "POST"}, name="route.cabinet.robokassa.settings")
     * @param Request $request
     * @return Response
     */
    public function settings(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(RobokassaType::class, $user->getRobokassaSettings());

        $result = $this->robokassaService->save($user, $request, $form);
        if ($result) {
            $this->addFlash('success', 'Настройки робокассы успешно сохранены.');
        } elseif ($result === false) {
            $this->addFlash('danger', 'Произошла ошибка при сохранении настроек робокассы');
        }

        return $this->render('pages/cabinet/robokassa/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}