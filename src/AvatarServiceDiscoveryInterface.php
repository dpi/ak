<?php

declare(strict_types = 1);

namespace dpi\ak;

use dpi\ak\Annotation\AvatarService;
use dpi\ak\AvatarKit\AvatarServices\AvatarServiceInterface;

/**
 * Discovers avatar services.
 */
interface AvatarServiceDiscoveryInterface {

  /**
   * Get all annotations.
   *
   * @return \dpi\ak\Annotation\AvatarService[]
   *   Get all discovered avatar services, keyed by class name.
   */
  public function getAvatarServices() : array;

  /**
   * Get metadata for an avatar service.
   *
   * @param string $id
   *   An avatar service ID.
   *
   * @return \dpi\ak\Annotation\AvatarService
   *   An avatar service annotation.
   *
   * @throws \dpi\ak\Exception\AvatarDiscoveryException
   *   Thrown if the service does not exist.
   */
  public function getMetadata(string $id) : AvatarService;

  /**
   * Create an instance of an avatar service.
   *
   * @param string $id
   *   An avatar service ID.
   *
   * @return \dpi\ak\AvatarKit\AvatarServices\AvatarServiceInterface
   *   An instance of an avatar service.
   *
   * @throws \dpi\ak\Exception\AvatarDiscoveryException
   *   Thrown if the service does not exist.
   */
  public function newInstance(string $id) : AvatarServiceInterface;

}
