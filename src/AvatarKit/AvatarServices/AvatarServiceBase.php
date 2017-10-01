<?php

declare(strict_types = 1);

namespace dpi\ak\AvatarKit\AvatarServices;

use dpi\ak\AvatarConfigurationInterface;
use dpi\ak\AvatarIdentifier;
use dpi\ak\AvatarIdentifierInterface;

/**
 * Common functionality for avatar services.
 */
abstract class AvatarServiceBase implements AvatarServiceInterface {

  /**
   * Configuration for this service.
   *
   * @var \dpi\ak\AvatarConfigurationInterface
   */
  protected $configuration;

  /**
   * {@inheritdoc}
   */
  public function createIdentifier() : AvatarIdentifierInterface {
    return new AvatarIdentifier();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration(): AvatarConfigurationInterface {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(AvatarConfigurationInterface $configuration) {
    $this->configuration = $configuration;
  }

}
