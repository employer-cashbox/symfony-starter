<?php declare(strict_types=1);

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
        return $this->render('pages/index/index.html.twig');
    }

    /**
     * @Route("/how_to_start", name="route.index.how_to_start")
     */
    public function howToStart()
    {
        return $this->render('pages/index/how_to_start.html.twig');
    }
}
