<?php declare(strict_types=1);


namespace App\Controller\Api\Cabinet;


use App\Exception\UserDoesNotHaveThisProductException;
use App\Service\RobokassaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RobokassaController
 * @package App\Controller\Api\Cabinet
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
     * Удаление товара
     * @Route(
     *     path="/api/cabinet/robokassa/get_code/{productId<\d+>}",
     *     methods={"GET"},
     *     name="route.api.cabinet.robokassa.get_code"
     * )
     * @param Request $request
     * @return Response
     */
    public function getCode(Request $request): Response
    {
        $url = '';
        $user = $this->getUser();
        $productId = (int)$request->get('productId');

        try {
            $url = $this->robokassaService->generateUrl($user, $productId);
        } catch (UserDoesNotHaveThisProductException $e) {
            $messages = 'У данного пользователя нет такого товара.';
        }

        return new JsonResponse([
            'status' => $url ? 'success' : 'error',
            'messages' => $messages ?? null,
            'url' => $url,
        ]);
    }
}