<?php
namespace Drupal\component_hub\Plugin\ContentWidget\TextArea;

use Drupal\component_hub\Plugin\ContentWidgetBase;
use Drupal\component_hub\Plugin\ContentWidgetInterface;

/**
 * Class TextArea
 *
 * @ContentWidgetAnnotation(
 *   id = "textarea",
 *   label = @Translation("TextArea"),
 *   admin_label = @Translation("TextArea Widget"),
 *   fields_display = 1,
 *   category = "brand",
 *   description = @Translation("TextArea Widget"),
 *   entity = "block_content:textarea",
 *   hidden = {"node:product"},
 *   plugin_path = {
 *     "type" = "module",
 *     "name" = "component_hub",
 *     "directory" = "src/Plugin/ContentWidget/TextArea",
 *   },
 *   template = "template",
 *   preview = "preview.jpg",
 *   widget = {
 *     "css" = "widget.css",
 *     "js" = "widget.js",
 *     "dependencies" = {
 *     }
 *   },
 *   front = {
 *     "css" = "front.css",
 *     "js" = "front.js",
 *     "dependencies" = {
 *       "drupal/jquery"
 *     }
 *   }
 * )
 */
class Component extends ContentWidgetBase implements ContentWidgetInterface{
  /**
   * {@inheritdoc}
   */
  protected function widgetView(&$build) {
    // $build['content']['#block_content'];
  }
}
