<?php

declare(strict_types = 1);

use dpi\ak\AvatarConfiguration;
use dpi\ak\AvatarServiceDiscoveryInterface;
use dpi\ak\AvatarServiceFactory;
use dpi\ak\AvatarKit\AvatarServices\AvatarServiceInterface;
use dpi\ak\Annotation\AvatarService;
use dpi\ak\Exception\AvatarDiscoveryException;
use dpi\ak\Exception\AvatarFactoryException;

/**
 * Tests avatar service factory.
 */
class AvatarServiceFactoryTest extends PHPUnit_Framework_TestCase {

  /**
   * Tests non-existent services bubble exceptions.
   *
   * Ensures the exception is not caught in createService.
   */
  public function testInvalidService() {
    $discovery = $this->getMockBuilder(AvatarServiceDiscoveryInterface::class)
      ->getMock();
    $exception = new AvatarDiscoveryException();
    $discovery->expects($this->once())
      ->method('getClass')
      ->willThrowException($exception);

    $factory = new AvatarServiceFactory($discovery);
    $this->setExpectedException(AvatarDiscoveryException::class);
    $factory->createService('id', new AvatarConfiguration());
  }

  /**
   * Validates requested dimensions against plugin constraints.
   *
   * @param array $protocols
   *   The protocol constraint of the plugin.
   * @param string|null $protocol
   *   The requested protocol.
   * @param bool $exception
   *   Whether this combination throws an exception.
   *
   * @dataProvider protocolValidatorMap
   */
  public function testProtocolValidator(array $protocols, ?string $protocol, bool $exception) {
    $discovery = $this->getMockBuilder(AvatarServiceDiscoveryInterface::class)
      ->getMock();

    $fake_service = new AvatarService();
    $fake_service->protocols = $protocols;
    $discovery
      ->method('getMetadata')
      ->willReturn($fake_service);

    $factory = $this->getMockBuilder(AvatarServiceFactory::class)
      ->setMethods(['newInstance'])
      ->setConstructorArgs([$discovery])
      ->getMock();

    $configuration = new AvatarConfiguration();
    if ($protocol) {
      $configuration->setProtocol($protocol);
    }

    if ($exception) {
      $this->setExpectedException(AvatarFactoryException::class, 'Invalid or undefined protocol.');
    }
    $service = $factory->createService('id', $configuration);

    if (!$exception) {
      $this->assertInstanceOf(AvatarServiceInterface::class, $service);
    }
  }

  /**
   * Map for validating protocol.
   *
   * @return array
   *   A data provider map.
   */
  public function protocolValidatorMap() {
    return [
      [['foo', 'bar'], 'foo', FALSE],
      [['foo', 'bar'], 'baz', TRUE],
    ];
  }

  /**
   * Validates requested dimensions against plugin constraints.
   *
   * @param string|null $dimensions
   *   The dimension constraint of the plugin.
   * @param int|null $width
   *   The requested width.
   * @param int|null $height
   *   The requested height.
   * @param bool $exception
   *   Whether this combination throws an exception.
   *
   * @dataProvider dimensionValidatorMap
   */
  public function testDimensionValidator(?string $dimensions, ?int $width, ?int $height, bool $exception) {
    $discovery = $this->getMockBuilder(AvatarServiceDiscoveryInterface::class)
      ->getMock();

    $fake_service_annotation = new AvatarService();
    $fake_service_annotation->dimensions = $dimensions;
    $discovery
      ->method('getMetadata')
      ->willReturn($fake_service_annotation);

    $factory = $this->getMockBuilder(AvatarServiceFactory::class)
      ->setMethods(['newInstance'])
      ->setConstructorArgs([$discovery])
      ->getMock();

    $configuration = new AvatarConfiguration();
    if ($width) {
      $configuration->setWidth($width);
    }
    if ($height) {
      $configuration->setHeight($height);
    }

    if ($exception) {
      $this->setExpectedException(AvatarFactoryException::class, 'Requested dimensions failed validation');
    }
    $service = $factory->createService('id', $configuration);

    if (!$exception) {
      $this->assertInstanceOf(AvatarServiceInterface::class, $service);
    }
  }

  /**
   * Map for validating dimensions.
   *
   * @return array
   *   A data provider map.
   */
  public function dimensionValidatorMap() {
    return [
      'Width outside minimum'  => ['400x300-800x600', 399, NULL, TRUE],
      'Height outside minimum' => ['400x300-800x600', NULL, 299, TRUE],
      'Width outside maximum'  => ['400x300-800x600', 801, NULL, TRUE],
      'Height outside maximum' => ['400x300-800x600', NULL, 601, TRUE],
      'Width exact minimum'    => ['400x300-800x600', 400, NULL, FALSE],
      'Height exact minimum'   => ['400x300-800x600', NULL, 300, FALSE],
      'Width exact maximum'    => ['400x300-800x600', 800, NULL, FALSE],
      'Height exact maximum'   => ['400x300-800x600', NULL, 600, FALSE],
      'Width within'           => ['400x300-800x600', 600, NULL, FALSE],
      'Height within'          => ['400x300-800x600', NULL, 450, FALSE],
      'Unconstrained'          => [NULL, 9999999999, 9999999999, FALSE],
    ];
  }

}
