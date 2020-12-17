<?php

namespace Drupal\component_hub\Controller;

use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContentWidgetController {
  /**
   * Component list page
   */
  public function lists() {
    $type = \Drupal::service('plugin.manager.content_widget');
    $plugin_definitions = $type->getDefinitions();

    foreach ($plugin_definitions as $plugin_definition) {
      $data[] = [
        'title' => $plugin_definition['admin_label'],
        'id' => $plugin_definition['id'],
        'category' => $plugin_definition['category'],
      ];
    }

    return [
      '#theme' => 'table',
      '#header' => ['Title', 'id', 'category'],
      '#rows' => $data,
    ];
  }

  /**
   * Component json. for debug only.
   *
   */
  public function json() {
    $type = \Drupal::service('plugin.manager.content_widget');
    $plugin_definitions = $type->getDefinitions();
    return new JsonResponse($plugin_definitions);
  }
}