<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct() {}

    #[Route("/", name:"home_page")]
    public function homePage(): Response
    {
        // prepare the template with d3js
        return $this->render('base.html.twig', [
            'page_title' => 'Bump Chart Demo',
            'page_content' => '<h1>Bump Chart</h1><p>Test</p><div id="chart"></div>'
        ]);
    }
}