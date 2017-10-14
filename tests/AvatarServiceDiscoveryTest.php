<?php

declare(strict_types = 1);

use dpi\ak\Annotation\AvatarService;
use dpi\ak\AvatarKit\AvatarServices\AvatarServiceInterface;
use dpi\ak\AvatarServiceDiscovery;

/**
 * Tests avatar service discovery.
 *
 * @coversDefaultClass \dpi\ak\AvatarServiceDiscovery
 */
class AvatarServiceDiscoveryTest extends PHPUnit_Framework_TestCase {

  /**
   * Basic discovery test.
   */
  public function testDiscovery() {
    $autoloader = require __DIR__ . '../../vendor/autoload.php';

    // Make sure the Composer autoloader has the expected keys.
    $prefixes = $autoloader->getPrefixesPsr4();
    $this->assertArrayHasKey('dpi\ak\\', $prefixes);
    $this->assertArrayHasKey('dpi\ak_test\\', $prefixes);

    $discovery = new AvatarServiceDiscovery(
      $prefixes,
      'AvatarKit/AvatarServices/',
      AvatarServiceInterface::class,
      AvatarService::class
    );
    $services = $discovery->getAvatarServices();
    $key = 'dpi\ak_test\AvatarKit\AvatarServices\FakeAvatarService';
    $this->assertArrayHasKey($key, $services);
  }

  /**
   * Test discovery with a non-existent directory.
   *
   * Composer autoloader doesn't necessarily contain valid directories.
   */
  public function testDiscoveryNonExistentDirectory() {
    $namespaces = [];
    $namespaces['dpi\decoy'][] = __DIR__ . '/my/decoy/directory/';

    // Discovery throws exceptions here if we did not filter out non-exist
    // directories.
    $discovery = new AvatarServiceDiscovery(
      $namespaces,
      'AvatarKit/AvatarServices/',
      AvatarServiceInterface::class,
      AvatarService::class
    );

    $services = $discovery->getAvatarServices();
    $this->assertSame([], $services);
  }

  /**
   * Test getClass method.
   *
   * @covers ::getClass
   */
  public function testGetClass() {
    $class = 'Fake\Class\Name';
    $id = 'id';
    $discovery = $this->getMockBuilder(AvatarServiceDiscovery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAvatarServices'])
      ->getMock();

    $fake_service_annotation = new AvatarService();
    $fake_service_annotation->id = $id;
    $discovery->expects($this->once())
      ->method('getAvatarServices')
      ->willReturn([$class => $fake_service_annotation]);

    $this->assertSame($class, $discovery->getClass($id));
  }

}
