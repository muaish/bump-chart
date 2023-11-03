<?php

namespace App\Service;

class AppUtil
{
  public static function getData($filepath)
  {
    $raw = DataUtil::readExcelSheets($filepath);
    $config = [];
    foreach ($raw['config'] as $r) {
      $k = array_shift($r);
      $config[$k] = $r;
    }

    $stages = [];
    $data = [];
    $n_stage = count($config['sequence-stage']);
    foreach ($config['sequence-stage'] as $i => $v) {
      $rows = $raw[$v];
      $keys = array_flip(array_shift($rows));
      $k_label = $keys[$config['stage label'][$i]];
      $k_value = $keys[$config['stage value'][$i]];
      foreach ($rows as $row) {
        if (!isset($data[$row[$k_label]])) {
          $data[$row[$k_label]] = array_fill(0, $n_stage, null);
        }
        $data[$row[$k_label]][$i] = $row[$k_value];
      }
      $stages[] = [
        'label' => $config['sequence-label'][$i],
        'max' => $config['stage limit'][$i],
      ];
    }
    $items = [];
    foreach ($data as $k => $v) {
      $items[] = [
        'label' => $k,
        'data' => $v
      ];
    }
    $ret = [
      'stages' => $stages,
      'items' => $items,
      'color' => array_map(function($x){ return $x[0]; }, $raw['color']),
    ];
    // return $raw;
    return $ret;
  }
}
