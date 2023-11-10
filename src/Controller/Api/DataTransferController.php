<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AppUtil;

class DataTransferController extends AbstractController
{
    #[Route("/api/transfer", name: "api_data_transfer", methods: "POST")]
    public function handleApiRequest(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        $filePath = $filePath = $this->getParameter('kernel.project_dir') . '/data/dataConfig.json';

        if (file_put_contents($filePath, $jsonData) === false) {
            return new JsonResponse(['error' => 'Failed to save data to JSON file'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse(['message' => 'Data received and processed'], Response::HTTP_OK);
    }
}
