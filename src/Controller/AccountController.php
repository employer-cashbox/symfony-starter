<?php
/**
 * Created by PhpStorm.
 * User: webby
 * Date: 14/10/2018
 * Time: 4:40 AM
 */

namespace App\Controller;


use App\Exception\NotEqualsPasswordException;
use App\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @package App\Controller
 */
class AccountController extends AbstractController
{
    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * AccountController constructor.
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @Route("/account", methods="GET", name="route.account.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('pages/account/index.html.twig');
    }

    /**
     * @Route("/account/update", methods="POST", name="route.account.update")
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $userId = $this->getUser()->getId();
        $result = $this->accountService->update($userId, $request);
        $result
            ? $this->addFlash('success', 'Информация о профиле успешно обновлена')
            : $this->addFlash('danger', 'Произошла ошибка при обновлении профиля');

        return $this->redirectToRoute('route.account.index');
    }

    /**
     * @Route("/account/change_password", name="route.account.change_password")
     * @param Request $request
     * @return RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $userId = $this->getUser()->getId();
        try {
            $result = $this->accountService->changePassword($userId, $request);
            $result
                ? $this->addFlash('success', 'Пароль успешно изменен')
                : $this->addFlash('danger', 'При изменения пароля произошла ошибка');
        } catch (NotEqualsPasswordException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('route.account.index');
    }

    /**
     * @Route("/account/delete", name="route.account.delete")
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $user = $this->userRepository->find($this->getUser()->getId());
        $this->removeObject($user);

        $session = new Session();
        $session->clear();

        return $this->redirect('/');
    }

    /**
     * Delete object from the database
     * @param $object
     */
    public function removeObject($object)
    {
        $this->entityManager->remove($object);
        $this->entityManager->flush();
    }

    /**
     * Update the database
     * @param $object
     */
    public function persistObject($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}