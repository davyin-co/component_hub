<?php

namespace Drupal\component_hub\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Block annotation object.
 *
 * @ingroup block_api
 *
 * @Annotation
 */
class ContentWidgetAnnotation extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The administrative label of the block.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $admin_label = '';

  /**
   * The category in the admin UI where the block will be listed.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $category = '';

}
