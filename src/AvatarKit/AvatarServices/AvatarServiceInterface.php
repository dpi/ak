<?php

declare(strict_types = 1);

namespace dpi\ak\AvatarKit\AvatarServices;

use dpi\ak\AvatarConfigurationInterface;
use dpi\ak\AvatarIdentifierInterface;

/**
 * Interface ServiceInterface.
 */
interface AvatarServiceInterface {

  /**
   * Get the URI of an avatar.
   *
   * @param \dpi\ak\AvatarIdentifierInterface $identifier
   *   An identifier object.
   *
   * @return string
   *   The URI of the avatar.
   */
  public function getAvatar(AvatarIdentifierInterface $identifier) : string;

  /**
   * Creates a new identifier suitable for this plugin.
   *
   * @return \dpi\ak\AvatarIdentifierInterface
   *   A new identifier object.
   */
  public function createIdentifier() : AvatarIdentifierInterface;

  /**
   * Get the configuration for this service.
   *
   * @return \dpi\ak\AvatarConfigurationInterface
   *   A configuration object.
   */
  public function getConfiguration(): AvatarConfigurationInterface;

  /**
   * Set the configuration for this service.
   *
   * @param \dpi\ak\AvatarConfigurationInterface $configuration
   *   A configuration object.
   */
  public function setConfiguration(AvatarConfigurationInterface $configuration);

}
