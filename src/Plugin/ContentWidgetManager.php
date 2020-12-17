<?php

namespace Drupal\component_hub\Plugin;

use Drupal\Component\Plugin\FallbackPluginManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;


/**
 * Plugin type manager for all views handlers.
 */
class ContentWidgetManager extends DefaultPluginManager implements FallbackPluginManagerInterface {
  /**
   * Constructs a ContentWidgetManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/ContentWidget',
      $namespaces,
      $module_handler,
      'Drupal\component_hub\Plugin\ContentWidgetInterface',
      'Drupal\component_hub\Annotation\ContentWidgetAnnotation'
    );
    $this->alterInfo('content_widget_info');
    $this->setCacheBackend($cache_backend, 'content_widget_info_plugins');
  }
  /**
   * {@inheritdoc}
   */
  public function getFallbackPluginId($plugin_id, array $configuration = []) {
    return 'broken';
  }

  /**
   * 获取所有widget plngin相关的所有entity
   */
  public function referencedEntities($plugin_id = null) {
    $entities = [];
    if($plugin_id) {
      $plugin_definitions = [$this->getDefinition($plugin_id)];
    } else {
      $plugin_definitions = $this->getDefinitions();
    }
    foreach($plugin_definitions as $plugin_definition) {
      if(isset($plugin_definition['entity'])) {
        list($entity_type, $bundle) = explode(':', $plugin_definition['entity']);
        $definition = \Drupal::service('entity.manager')->getDefinition($entity_type);
        $bundle_key = $definition->getKey('bundle');
        $storage = \Drupal::service('entity.manager')->getStorage($entity_type);
        $ids = $storage->getQuery()->condition($bundle_key, $bundle)->execute();
        foreach($ids as $id) {
          $entity = $storage->load($id);
          $entities[] = $entity;
        }
      }
    }
    return $entities;
  }

  /**
   * 通过entity名获取相关plugin
   */
  public function getPluginByEntityName($name) {
    $plugin_definitions = $this->getDefinitions();
    foreach($plugin_definitions as $plugin_id => $plugin_definition) {
      if(isset($plugin_definition['entity']) && $plugin_definition['entity'] == $name) {
        return $this->createInstance($plugin_id);
      }
    }
    return null;
  }
}
