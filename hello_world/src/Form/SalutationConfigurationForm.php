<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configuration form definition for the salutation message.
 */
class SalutationConfigurationForm extends ConfigFormBase {

  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * SalutationConfigurationForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['hello_world.custom_configuration'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('hello_world.custom_configuration');

    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#required' => TRUE,
      '#default_value' => $config->get('country'),
      '#description' => $this->t('User Country'),
      '#weight' => '0',
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#required' => TRUE,
      '#default_value' => $config->get('city'),
      '#description' => $this->t('User City'),
      '#weight' => '1',
    ];

    $options = [
      'America/Chicago' => 'America/Chicago',
      'America/New_York' => 'America/New_York',
      'Asia/Tokyo' => 'Asia/Tokyo',
      'Asia/Dubai' => 'Asia/Dubai',
      'Asia/Kolkata' => 'Asia/Kolkata',
      'Europe/Amsterdam' => 'Europe/Amsterdam',
      'Europe/Oslo' => 'Europe/Oslo',
      'Europe/London' => 'Europe/London'
    ];

    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#required' => TRUE,
      '#default_value' => $config->get('timezone'),
      '#options' => $options,
      '#weight' => '2',
    ];


    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {


    foreach ($form_state->getValues() as $key => $value) {


      if($key == 'country' && strlen($value) > 20){

        $form_state->setErrorByName('country', $this->t('Country name is too long'));

      }
      elseif($key == 'country' && strlen($value) < 3){

        $form_state->setErrorByName('country', $this->t('Country name is too short'));

      }

      if($key == 'city' && strlen($value) > 20){

        $form_state->setErrorByName('city', $this->t('City name is too long'));

      }
      elseif($key == 'city' && strlen($value) < 3){

        $form_state->setErrorByName('city', $this->t('City name is too short'));
      }

      if($key == 'timezone' && $value == ''){

        $form_state->setErrorByName('timezone', $this->t('Please select timezone'));

      }
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   $config =  $this->config('hello_world.custom_configuration');

      $config->set('country', $form_state->getValue('country'))->save();
      $config->set('city', $form_state->getValue('city'))->save();
      $config->set('timezone', $form_state->getValue('timezone'))->save();

    parent::submitForm($form, $form_state);
   # $this->logger->info('The Hello World salutation has been changed to @message', ['@message' => $form_state->getValue('salutation')]);
  }
}
