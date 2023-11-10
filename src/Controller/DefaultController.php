<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;

class DefaultController extends AbstractController

{
    public function __construct()
    {
    }

    #[Route("/", name: "home_page")]
    public function homePage(): Response
    {
        $dataFolderPath = $this->getParameter('kernel.project_dir') . '/data';

        $finder = new Finder();
        $finder->in($dataFolderPath)->files()->name('*.xlsx');

        $filename = [];
        foreach ($finder as $file) {
            $filename = $file->getRelativePathname();
            //not making the temporary files to be included
            if (strpos($filename, "~$") !== 0) {
                $files[] = $filename;
            }
        }

        return $this->render('base.html.twig', [
            'page_title' => 'Bump Chart Demo',
            'page_content' => '<h1>Bump Chart</h1><div id="chart"></div>',
            'files' => $files,
        ]);
    }
}
