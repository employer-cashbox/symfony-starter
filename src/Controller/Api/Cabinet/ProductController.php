<?php declare(strict_types=1);


namespace App\Controller\Api\Cabinet;


use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller\Api\Cabinet
 */
class ProductController extends AbstractController
{
    /** @var ProductService */
    private ProductService $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Удаление товара
     * @Route(
     *     path="/api/cabinet/product/delete/{productId<\d+>}",
     *     methods={"DELETE"},
     *     name="route.api.cabinet.product.delete"
     * )
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request): Response
    {
        $user = $this->getUser();
        $productId = (int)$request->get('productId');

        $result = $this->productService->delete($user, $productId);

        return new JsonResponse([
            'status' => $result ? 'success' : 'error',
        ]);
    }
}