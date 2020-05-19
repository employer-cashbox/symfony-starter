<?php declare(strict_types=1);

namespace App\Controller\Cabinet;

use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TransactionController
 * @package App\Controller
 */
class TransactionController extends AbstractController
{
    /** @var TransactionService */
    private TransactionService $transactionService;

    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /**
     * TransactionController constructor.
     * @param TransactionService  $transactionService
     * @param SerializerInterface $serializer
     */
    public function __construct(TransactionService $transactionService, SerializerInterface $serializer)
    {
        $this->transactionService = $transactionService;
        $this->serializer = $serializer;
    }

    /**
     * Список товаров
     * @Route("/cabinet/transaction/list", methods={"GET"}, name="route.cabinet.transaction.list")
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        $user = $this->getUser();
        $page = (int)$request->get('page', 1);
        $elementOnPage = $request->get('element_on_page', $this->getParameter('transaction')['max_result_on_page']);

        $transactionTotal = $this->transactionService->getTotal($user);
        $transactionList = $this->transactionService->getList($user, $page, $elementOnPage);

        return $this->render('pages/cabinet/transaction/list.html.twig', [
            'transactionTotal' => $transactionTotal,
            'transactionList' => $transactionList,
            'page' => $page,
            'elementOnPage' => $elementOnPage,
        ]);
    }
}
