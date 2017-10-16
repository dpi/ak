<?php

declare(strict_types = 1);

use dpi\ak\AvatarIdentifier;
use dpi\ak\Exception\AvatarIdentifierException;

/**
 * Tests avatar identifier class.
 *
 * @coversDefaultClass \dpi\ak\AvatarIdentifier
 */
class AvatarIdentifierTest extends PHPUnit_Framework_TestCase {

  /**
   * Tests no hasher.
   */
  public function testHasherUndefined() {
    $identifier = $this->createAvatarIdentifier();

    // Tests no hasher.
    $identifier->setRaw('xyz');

    $this->setExpectedException(AvatarIdentifierException::class, 'No hashing algorithm set.');
    $identifier->getHashed();
  }

  /**
   * Tests no raw value.
   */
  public function testRawUndefined() {
    $identifier = $this->createAvatarIdentifier();

    $this->setExpectedException(AvatarIdentifierException::class, 'No values set.');
    $identifier->getHashed();
  }

  /**
   * Creates a new avatar identifier..
   *
   * @return \dpi\ak\AvatarIdentifierInterface
   *   A new avatar identifier object.
   */
  protected function createAvatarIdentifier() {
    return new AvatarIdentifier();
  }

}
