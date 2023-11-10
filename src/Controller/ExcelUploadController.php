<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use App\Form\FileUploadType;
use Symfony\Component\Routing\Annotation\Route;

class ExcelUploadController extends AbstractController
{

  #[Route("/upload", name: "app_upload")]
  public function excelCommunesAction(Request $request, FileUploader $file_uploader)
  {
    $form = $this->createForm(FileUploadType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $file = $form['upload_file']->getData();

      if ($file) {

        $file_uploader->upload($file);
        return $this->redirectToRoute('app_upload');
      }
    }
    return $this->render('upload.html.twig', [
      'form' => $form->createView(),
    ]);
  }
}
