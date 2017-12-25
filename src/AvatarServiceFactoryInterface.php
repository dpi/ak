<?php

declare(strict_types = 1);

namespace dpi\ak;

use dpi\ak\AvatarKit\AvatarServices\AvatarServiceInterface;

/**
 * Avatar service factory interface.
 */
interface AvatarServiceFactoryInterface {

  /**
   * Create a service instance.
   *
   * Creates a new service instance while ensuring that the instance is
   * configured correctly, according to annotation metadata.
   *
   * Instances of this are designed to be reused many times. The only variance
   * being the identifier objects passed to the service.
   *
   * @param string $id
   *   A plugin id.
   * @param \dpi\ak\AvatarConfigurationInterface|null $configuration
   *   Avatar service configuration.
   *
   * @return \dpi\ak\AvatarKit\AvatarServices\AvatarServiceInterface
   *   A new service instance
   *
   * @throws \dpi\ak\Exception\AvatarDiscoveryException
   *   If the requested plugin does not exist.
   */
  public function createService(string $id, AvatarConfigurationInterface $configuration): AvatarServiceInterface;

}
