<?php

declare(strict_types = 1);

/**
 * Tests avatar service discovery.
 *
 * @coversDefaultClass \dpi\ak\AvatarServiceDiscovery
 */
class AvatarServiceDiscoveryTest extends PHPUnit_Framework_TestCase {

  /**
   *
   * @covers ::getClass
   */
  function testGetClass() {
    $class = 'Fake\Class\Name';
    $id = 'id';
    $discovery = $this->getMockBuilder(\dpi\ak\AvatarServiceDiscovery::class)
      ->disableOriginalConstructor()
      ->setMethods(['getAvatarServices'])
      ->getMock();

    $fake_service_annotation = new \dpi\ak\Annotation\AvatarService();
    $fake_service_annotation->id = $id;
    $discovery->expects($this->once())
      ->method('getAvatarServices')
      ->willReturn([$class => $fake_service_annotation]);

    $this->assertSame($class, $discovery->getClass($id));
  }

}
