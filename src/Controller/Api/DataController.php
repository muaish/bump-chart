<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AppUtil;
use Symfony\Component\HttpFoundation\Request;

class DataController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route("/api/data/load", name: "api_data_load", methods: "POST")]
    public function apiData(
        AppUtil $util,
        #[Autowire('%kernel.project_dir%')]
        string $project_dir,
        Request $request
    ): JsonResponse {

        $selectedFile = $request->request->get('selectedFile');

        if (empty($selectedFile)) {

            return new JsonResponse(['error' => 'No file selected'], 400);
        }
        $filepath = implode(DIRECTORY_SEPARATOR, [$project_dir, 'data', $selectedFile]);
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
        $jsonData = file_get_contents($filepath);
        $data = json_decode($jsonData, true);

        if ($data === null) {
            throw new \Exception('Invalid JSON file');
        }
        return new JsonResponse($data);
    }
}
