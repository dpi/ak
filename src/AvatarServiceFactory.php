<?php

declare(strict_types = 1);

namespace dpi\ak;

use dpi\ak\AvatarKit\AvatarServices\AvatarServiceInterface;
use dpi\ak\Exception\AvatarFactoryException;

/**
 * Avatar service factory.
 */
class AvatarServiceFactory implements AvatarServiceFactoryInterface {

  /**
   * Avatar service discovery.
   *
   * @var \dpi\ak\AvatarServiceDiscoveryInterface
   */
  protected $discovery;

  /**
   * AvatarServiceFactory constructor.
   *
   * @param \dpi\ak\AvatarServiceDiscoveryInterface $avatarServiceDiscovery
   *   Avatar service discovery.
   */
  public function __construct(AvatarServiceDiscoveryInterface $avatarServiceDiscovery) {
    $this->discovery = $avatarServiceDiscovery;
  }

  /**
   * {@inheritdoc}
   */
  public function createService(string $id, AvatarConfigurationInterface $configuration) : AvatarServiceInterface {
    // @todo if plugin is remote, require a destination for avatars.
    $metadata = $this->discovery->getMetadata($id);

    // Protocol.
    if (!empty($metadata->protocols)) {
      $protocols = $metadata->protocols;

      $protocol = $configuration->getProtocol();
      if (is_string($protocol)) {
        if (!in_array($protocol, $protocols)) {
          throw new AvatarFactoryException('Invalid or undefined protocol.');
        }
      }
    }

    // Dimensions.
    $width = $configuration->getWidth();
    $height = $configuration->getHeight();
    if ($width || $height) {
      if (!empty($metadata->dimensions)) {
        // Dimensions validator.
        $regex = '/^(?<minx>\d*)x(?<miny>\d*)\-(?<maxx>\d*)x(?<maxy>\d*)$/D';
        $dimensions = $metadata->dimensions;
        preg_match_all($regex, $dimensions, $matches, PREG_SET_ORDER);
        $valid_x = !$width || ($width >= $matches[0]['minx'] && $width <= $matches[0]['maxx']);
        $valid_y = !$height || ($height >= $matches[0]['miny'] && $height <= $matches[0]['maxy']);
        if (!($valid_x && $valid_y)) {
          throw new AvatarFactoryException('Requested dimensions failed validation.');
        }
      }
    }

    $class = $this->discovery->getClass($id);
    $instance = $this->newInstance($class, $configuration);

    return $instance;
  }

  /**
   * Create a new instance of a avatar service.
   *
   * @param string $class
   *   The class to instantiate.
   * @param \dpi\ak\AvatarConfigurationInterface $configuration
   *   The configuration of the avatar service.
   *
   * @return \dpi\ak\AvatarKit\AvatarServices\AvatarServiceInterface
   *   Return a new avatar service.
   */
  protected function newInstance(string $class, AvatarConfigurationInterface $configuration) : AvatarServiceInterface {
    return new $class($configuration);
  }

}
