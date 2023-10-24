<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Service\AppUtil;

class tryAppUtilGetData extends Command
{
  protected static $defaultName = 'try:app_util:get_data';
  private $project_dir;

  public function __construct(
    #[Autowire('%kernel.project_dir%')]
    string $project_dir
  )
  {
    parent::__construct();
    $this->project_dir = $project_dir;
  }

  protected function execute(
    InputInterface $input,
    OutputInterface $output
  ) {
    $filepath = implode(DIRECTORY_SEPARATOR, [$this->project_dir, 'data', 'data-2.xlsx']);
    $data = AppUtil::getData($filepath);
    echo json_encode($data);
    return 0;
  }
}
