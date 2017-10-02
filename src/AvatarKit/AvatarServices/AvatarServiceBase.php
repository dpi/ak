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
   * Constructs a new Avatar service.
   *
   * @param \dpi\ak\AvatarConfigurationInterface $configuration
   *   The avatar service configuration.
   */
  public function __construct(AvatarConfigurationInterface $configuration) {
    $this->configuration = $configuration;
  }

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

}
