<?php

declare(strict_types = 1);

use dpi\ak\AvatarConfiguration;
use dpi\ak\AvatarConfigurationInterface;

/**
 * Tests avatar configuration class.
 *
 * @coversDefaultClass \dpi\ak\AvatarConfiguration
 */
class AvatarConfigurationTest extends PHPUnit_Framework_TestCase {

  /**
   * Tests width.
   */
  public function testWidth() {
    $configuration = $this->createAvatarConfiguration();

    $default = $configuration->getWidth();
    $this->assertNull($default);

    $width = 999;
    $return = $configuration->setWidth($width);
    $this->assertInstanceOf(AvatarConfigurationInterface::class, $return);

    $this->assertSame($width, $configuration->getWidth());
  }

  /**
   * Tests height.
   */
  public function testHeight() {
    $configuration = $this->createAvatarConfiguration();

    $default = $configuration->getHeight();
    $this->assertNull($default);

    $height = 999;
    $return = $configuration->setHeight($height);
    $this->assertInstanceOf(AvatarConfigurationInterface::class, $return);

    $this->assertSame($height, $configuration->getHeight());
  }

  /**
   * Tests protocol.
   */
  public function testProtocol() {
    $configuration = $this->createAvatarConfiguration();

    $default = $configuration->getProtocol();
    $this->assertNull($default);

    $protocol = 'a';
    $return = $configuration->setProtocol($protocol);
    $this->assertInstanceOf(AvatarConfigurationInterface::class, $return);

    $this->assertSame($protocol, $configuration->getProtocol());
  }

  /**
   * Creates a new avatar configuration.
   *
   * @return \dpi\ak\AvatarConfigurationInterface
   *   A new avatar configuration object.
   */
  protected function createAvatarConfiguration() {
    return new AvatarConfiguration();
  }

}
