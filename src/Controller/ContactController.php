<?php declare(strict_types=1);

namespace App\Controller;


use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @package App\Controller
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="route.contact.index")
     * @return Response
     */
    public function index()
    {
        return $this->render('pages/contact/index.html.twig');
    }

    /**
     * @Route("/contact/submit", name="route.contact.submit")
     * @param Request $request
     * @return RedirectResponse
     */
    public function submitForm(Request $request, Swift_Mailer $mailer)
    {
        $name = $request->get('name');
        $email = $request->get('email');

        $emailBody = $this->renderView('email/contact.html.twig', [
            'name' => $name,
        ]);

        $message = (new Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo($email)
            ->setBody($emailBody, 'text/html');
        $mailer->send($message);

        $this->addFlash('success', 'Email отправлен успешно!');

        return $this->redirectToRoute('route.contact.index');
    }
}