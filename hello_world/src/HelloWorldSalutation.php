<?php

namespace Drupal\hello_world;

use DateTime;
use DateTimeZone;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Prepares the salutation to the world.
 */
class HelloWorldSalutation {

  use StringTranslationTrait;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * @var \Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $killSwitch;

  /**
   * HelloWorldSalutation constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   * @param \Drupal\Core\PageCache\ResponsePolicy\KillSwitch $killSwitch
   */
  public function __construct(ConfigFactoryInterface $config_factory, EventDispatcherInterface $eventDispatcher, KillSwitch $killSwitch) {
    $this->configFactory = $config_factory;
    $this->eventDispatcher = $eventDispatcher;
    $this->killSwitch = $killSwitch;
  }

  /**
   * Returns the salutation
   */
  public function getSalutation() {
    $this->killSwitch->trigger();
    $config = $this->configFactory->get('hello_world.custom_configuration');
    $timezone = $config->get('timezone');

    if($timezone == ''){
      return $this->t('Please set timezone ');
    }
    $time = new DateTime();
    $timezoneObj = new DateTimeZone($timezone);
    $time->setTimezone($timezoneObj);
    $cTime =  $time->format('jS M Y - h:i A');


    if ((int) $time->format('G') >= 06 && (int) $time->format('G') < 12) {
      return $this->t('Good morning  # ' . $cTime);
    }

    if ((int) $time->format('G') >= 12 && (int) $time->format('G') < 18) {
      return $this->t('Good afternoon  # ' . $cTime);
    }

    if ((int) $time->format('G') >= 18) {
      return $this->t('Good evening  # ' . $cTime);
    }
  }


}
