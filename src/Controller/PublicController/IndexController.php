<?php

namespace App\Controller\PublicController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(){

        return $this->render('page/index.html.twig');
    }


}