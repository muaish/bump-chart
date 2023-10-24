<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DataUtil;

class DataController extends AbstractController
{
    public function __construct() {}

    #[Route("/api/data", name:"api_data")]
    public function apiData(
        DataUtil $data_util,
        #[Autowire('%kernel.project_dir%')]
        string $project_dir
    ): JsonResponse
    {
        // Retrieve data from xlsx file
        $filepath = implode(DIRECTORY_SEPARATOR, [$project_dir, 'data', 'data.xlsx']);
        $raw = $data_util::readExcelSheets($filepath);
        return new JsonResponse($raw);
    }
}