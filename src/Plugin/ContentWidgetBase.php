<?php

namespace Drupal\component_hub\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Tag cloud plugins.
 */
abstract class ContentWidgetBase extends PluginBase implements ContentWidgetInterface {

  /**
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Retrieves the entity manager service.
   *
   * @return \Drupal\Core\Entity\EntityManagerInterface
   *   The entity manager service.
   *
   * @deprecated in Drupal 8.0.0, will be removed before Drupal 9.0.0.
   *   Most of the time static::entityTypeManager() is supposed to be used
   *   instead.
   */
  protected function entityManager() {
    if (!$this->entityManager) {
      $this->entityManager = $this->container()->get('entity.manager');
    }
    return $this->entityManager;
  }

  /**
   * {@inheritdoc}
   */
  public function blockViewAlter(&$build) {
    if($this->isFieldsDisplay()) {
      $build['#theme'] = $this->getTemplate();
    } else {
      $build['content'] = [
        '#block_content' => $build['content']['#block_content'],
        '#theme' => $this->getTemplate(),
      ];
    }
    if($this->get('front')) {
      $build['content']['#attached']['library'][] = $this->get('provider') . '/' . $this->get('provider') . '.' . $this->get('id') . '.front';
    }
    $this->initSpacer($build['content']);
    $this->widgetView($build['content']);
    foreach ($build['content'] as $key => $item) {
      if($key === '' || $key[0] !== '#') {
        if(is_string($build['content'][$key])) {
          $build['content'][$key] = [
            '#markup' => $build['content'][$key]
          ];
        }
      }
    }
    
  }

  protected function widgetView(&$build) {}

  /**
   * @return string
   *   Template name.
   */
  public function getTemplate() {
    return 'component_hub_'.$this->getPluginDefinition()['id'];
  }

  /**
   * @return bool
   *   是否清空输出字段
   */
  public function isFieldsDisplay() {
    $definition = $this->getPluginDefinition();
    return $definition['fields_display'] ?? false;
  }

  /**
   * @return string|boolean
   *   Preview image path.
   */
  public function getPreviewImage() {
    $preview = $this->get('preview');
    if($preview) {
      $host = \Drupal::request()->getSchemeAndHttpHost();
      $baseurl = \Drupal::request()->getBaseUrl();
      if($baseurl) {
        $host .= $baseurl;
      }

      return $host.DIRECTORY_SEPARATOR.$this->getPluginPath().DIRECTORY_SEPARATOR.$preview;
    }
    return false;
  }

  /**
   * @return string
   *   Definition value
   */
  public function get($name) {
    $pluginDefinition = $this->getPluginDefinition();
    return isset($pluginDefinition[$name]) ? $pluginDefinition[$name] : null;
  }

  /**
   * @return string
   *   Template file path.
   */
  protected function getTemplatePath() {
    $plugin_path = $this->getPluginPath();
    $template = $this->getPluginDefinition()['template'];
    return $plugin_path . DIRECTORY_SEPARATOR . $template . '.html.twig';
  }

  /**
   * get plugin path
   * @return string
   *   Plugin Path
   */
  protected function getPluginPath() {
    $plugin_path = $this->getPluginDefinition()['plugin_path'];
    return drupal_get_path($plugin_path['type'], $plugin_path['name']) . DIRECTORY_SEPARATOR . $plugin_path['directory'];
  }

  /**
   * Returns the service container.
   *
   * This method is marked private to prevent sub-classes from retrieving
   * services from the container through it. Instead,
   * \Drupal\Core\DependencyInjection\ContainerInjectionInterface should be used
   * for injecting services.
   *
   * @return \Symfony\Component\DependencyInjection\ContainerInterface
   *   The service container.
   */
  private function container() {
    return \Drupal::getContainer();
  }

  /**
   * Init spacer
   */
  protected function initSpacer(&$build)
  {
    if(empty($build['#block_content'])) return;
    $block_content = $build['#block_content'];
    //If exists field name ,set value
    $name = false;
    if ($block_content->hasField('field_spacer') && !$block_content->field_spacer->isEmpty()) {
      $name = $block_content->field_spacer->value;
    }elseif($block_content->hasField('spacer') && !$block_content->spacer->isEmpty()) {
      $name = $block_content->spacer->value;
    }
    if($name) {
      switch ($name) {
        case 'large_bottom':
          $build['#suffix'] = '<div class="spacer-lg"></div>'; //large_bottom|底部大空
          break;
        case 'large_top':
          $build['#prefix'] = '<div class="spacer-lg"></div>'; //large_top|顶部大空
          break;
        case 'large_both':
          $build['#prefix'] = '<div class="spacer-lg"></div>';
          $build['#suffix'] = '<div class="spacer-lg"></div>'; //large_both|上下大空
          break;
        case 'medium_bottom':
          $build['#suffix'] = '<div class="spacer-md"></div>'; //medium_bottom|底部中空
          break;
        case 'medium_top':
          $build['#prefix'] = '<div class="spacer-md"></div>'; //medium_top|顶部中空
          break;
        case 'medium_both':
          $build['#prefix'] = '<div class="spacer-md"></div>';
          $build['#suffix'] = '<div class="spacer-md"></div>'; //medium_both|上下中空
          break;
        case 'small_bottom':
          $build['#suffix'] = '<div class="spacer-sm"></div>'; //small_bottom|底部小空
          break;
        case 'small_top':
          $build['#prefix'] = '<div class="spacer-sm"></div>'; //small_top|顶部小空
          break;
        case 'small_both':
          $build['#prefix'] = '<div class="spacer-sm"></div>';
          $build['#suffix'] = '<div class="spacer-sm"></div>'; //small_both|上下小空
          break;
        default :
          break;
      }
    }
  }
}
