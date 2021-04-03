<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hello_world\HelloWorldSalutation as HelloWorldSalutationService;

/**
 * HHello world Custom Configuration Block.
 *
 * @Block(
 *  id = "hello_world_custom_configuration_block",
 *  admin_label = @Translation("Hello world Custom Configuration Block"),
 * )
 */
class HelloWorldCustomConfigurationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\hello_world\HelloWorldSalutation definition.
   *
   * @var \Drupal\hello_world\HelloWorldCustomConfigurationBlock
   */
  protected $salutation;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\hello_world\HelloWorldSalutation $salutation
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, HelloWorldSalutationService $salutation) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->salutation = $salutation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('hello_world.salutation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $build[] = [
      '#theme' => 'container',
      '#children' => [
        '#markup' => $this->salutation->getSalutation(),
      ]
    ];
/*
    $url = Url::fromRoute('hello_world.hide_block');
    $url->setOption('attributes', ['class' => 'use-ajax']);
    $build[] = [
      '#type' => 'link',
      '#url' => $url,
      '#title' => $this->t('Remove'),
    ];*/

    return $build;
  }
}
