<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AppUtil;

class DataController extends AbstractController
{
    public function __construct()
    {
    }

    // #[Route("/api/data", name:"api_data")]
    // public function apiData(
    //     AppUtil $util,
    //     #[Autowire('%kernel.project_dir%')]
    //     string $project_dir
    // ): JsonResponse
    // {
    //     // Retrieve data from xlsx file
    //     $filepath = implode(DIRECTORY_SEPARATOR, [$project_dir, 'data', 'data-2.xlsx']);
    //     $data = $util::getData($filepath);
    //     return new JsonResponse($data);
    // }

    #[Route("/api/data/{dataName}", name: "api_data")]
    public function apiData(
        AppUtil $util,
        #[Autowire('%kernel.project_dir%')]
        string $project_dir,
        string $dataName
    ): JsonResponse {
        // Retrieve data from xlsx file
        $filepath = implode(DIRECTORY_SEPARATOR, [$project_dir, 'data', $dataName . '.xlsx']);
        $data = $util::getData($filepath);
        return new JsonResponse($data);
    }

    #[Route("/api/json", name: "api_json")]
    public function apiDataJson(
        #[Autowire('%kernel.project_dir%')]
        string $project_dir
    ): JsonResponse {
        $filepath = implode(DIRECTORY_SEPARATOR, [$project_dir, 'data', 'dataConfig.json']);
        if (!file_exists($filepath)) {
            throw $this->createNotFoundException('JSON file not found');
        }
        // Read the JSON file
        $jsonData = file_get_contents($filepath);
        $data = json_decode($jsonData, true);

        if ($data === null) {
            throw new \Exception('Invalid JSON file');
        }
        return new JsonResponse($data);
    }
}
