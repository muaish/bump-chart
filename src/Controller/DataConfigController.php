<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataConfigController extends AbstractController
{
    
    #[Route("/config", name:"data_config")]
    public function dataConfig(): Response
    {
        return $this->render('form.html.twig');
    }
}