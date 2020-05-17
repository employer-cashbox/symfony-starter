<?php
/**
 * Created by PhpStorm.
 * User: webby
 * Date: 14/10/2018
 * Time: 4:40 AM
 */

namespace App\Controller;


use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CabinetController
 * @package App\Controller
 */
class CabinetController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $userRepository;

    /**
     * AccountController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('App:User');
    }

    /**
     * @Route("/cabinet", name="route.cabinet.index")
     */
    public function index()
    {
        return $this->render('pages/cabinet/index.html.twig');
    }
}