<?php
/**
 * Created by PhpStorm.
 * User: webby
 * Date: 14/10/2018
 * Time: 4:40 AM
 */

namespace App\Controller;


use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CabinetController
 * @package App\Controller
 */
class CabinetController extends AbstractController
{
    /** @var ProductService */
    private ProductService $productService;

    /**
     * AccountController constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/cabinet", name="route.cabinet.index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $page = (int)$request->get('page', 1);
        $elementOnPage = $request->get('element_on_page', $this->getParameter('product')['max_result_on_page']);

        $productTotal = $this->productService->getTotal($user);
        $productList = $this->productService->getList($user, $page, $elementOnPage);

        return $this->render('pages/cabinet/index.html.twig', [
            'productTotal' => $productTotal,
            'productList' => $productList,
            'page' => $page,
            'elementOnPage' => $elementOnPage,
        ]);
    }
}