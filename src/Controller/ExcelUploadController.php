<?php

namespace App\Controller;

use App\Form\ExcelUploadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelUploadController extends AbstractController
{
    public function uploadExcel(Request $request): Response
    {
        $form = $this->createForm(ExcelUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['file']->getData();
            $outputFilePath = ''; // Set the desired output path

            // Move the uploaded file to the desired location
            $file->move(\dirname($outputFilePath), \basename($outputFilePath));

            // Process the uploaded Excel file
            $spreadsheet = IOFactory::load($outputFilePath);
            // Add your processing logic here

            // Optionally, you can display a success message to the user
            $this->addFlash('success', 'Excel file uploaded and processed successfully.');

            return $this->redirectToRoute('your_success_route');
        }

        return $this->render('excel_upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
