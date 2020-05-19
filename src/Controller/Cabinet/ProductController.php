<?php declare(strict_types=1);

namespace App\Controller\Cabinet;

use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends AbstractController
{
    /** @var ProductService */
    private ProductService $productService;

    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /**
     * ProductController constructor.
     * @param ProductService      $productService
     * @param SerializerInterface $serializer
     */
    public function __construct(ProductService $productService, SerializerInterface $serializer)
    {
        $this->productService = $productService;
        $this->serializer = $serializer;
    }

    /**
     * Список товаров
     * @Route("/cabinet/product/list", methods={"GET"}, name="route.cabinet.product.list")
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        $user = $this->getUser();
        $page = (int)$request->get('page', 1);
        $elementOnPage = $request->get('element_on_page', $this->getParameter('product')['max_result_on_page']);

        $productTotal = $this->productService->getTotal($user);
        $productList = $this->productService->getList($user, $page, $elementOnPage);

        return $this->render('pages/cabinet/product/list.html.twig', [
            'productTotal' => $productTotal,
            'productList' => $productList,
            'page' => $page,
            'elementOnPage' => $elementOnPage,
        ]);
    }

    /**
     * Добавление товара
     * @Route("/cabinet/product/add", methods={"GET", "POST"}, name="route.cabinet.product.add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(ProductType::class);

        $result = $this->productService->add($this->getUser(), $request, $form);
        if ($result) {
            $this->addFlash('success', 'Товар довален успешно');
            return $this->redirectToRoute('route.cabinet.index');
        } elseif ($result === false) {
            $this->addFlash('danger', 'Произошла ошибка при добавлении товара');
        }

        return $this->render('pages/cabinet/product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Редактирование товара
     * @Route("/cabinet/product/edit/{product<\d+>}", methods={"GET", "POST"}, name="route.cabinet.product.edit")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function edit(Request $request, Product $product): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getId() !== $product->getUser()->getId()) {
            throw $this->createNotFoundException("Товар с ID: {$product->getId()} не найден");
        }

        $initialProduct = clone $product;
        $form = $this->createForm(ProductType::class, $product);

        $result = $this->productService->edit($user, $request, $product, $form);
        if ($result) {
            $this->addFlash('success', 'Товар успешно изменен.');
            return $this->redirectToRoute('route.cabinet.product.list');
        } elseif ($result === false) {
            $this->addFlash('danger', 'Произошла ошибка при редактировании товара');
        }

        return $this->render('pages/cabinet/product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $initialProduct,
        ]);
    }

    /**
     * Удаление товара
     * @Route("/cabinet/product/delete/{productId<\d+>}", methods={"GET"}, name="route.cabinet.product.delete")
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request): Response
    {
        $user = $this->getUser();
        $productId = (int)$request->get('productId');

        $this->productService->delete($user, $productId)
            ? $this->addFlash('success', 'Товар успешно удален.')
            : $this->addFlash('danger', 'Товар не был удален. Возможно вы пытаетесь удалить несуществующий товар');

        return $this->redirectToRoute('route.cabinet.product.list');
    }
}
