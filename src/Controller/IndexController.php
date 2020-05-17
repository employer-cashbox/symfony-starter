<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="route.index.index")
     */
    public function index()
    {
        return $this->render('pages/index/index.html.twig', [
            'controller_name' => 'IndexController'
        ]);
    }
}
