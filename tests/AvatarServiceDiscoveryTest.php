<?php

declare(strict_types = 1);

use dpi\ak\Annotation\AvatarService;
use dpi\ak\AvatarServiceDiscovery;

/**
 * Tests avatar service discovery.
 *
 * @coversDefaultClass \dpi\ak\AvatarServiceDiscovery
 */
class AvatarServiceDiscoveryTest extends PHPUnit_Framework_TestCase {

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
