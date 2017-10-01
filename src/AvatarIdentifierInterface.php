<?php

declare(strict_types = 1);

namespace dpi\ak;

/**
 * The avatar identifier interface.
 */
interface AvatarIdentifierInterface {

  /**
   * Get the raw identifier.
   *
   * @return string
   *   The raw identifier.
   *
   * @throws \dpi\ak\Exception\AvatarIdentifierException
   *   Thrown if no raw value is available.
   */
  public function getRaw();

  /**
   * Set the raw identifier.
   *
   * @param string $raw
   *   The raw identifier.
   *
   * @return $this
   *   This object.
   */
  public function setRaw(string $raw);

  /**
   * Get the hashed identifier.
   *
   * @return string
   *   The hashed identifier.
   *
   * @throws \dpi\ak\Exception\AvatarIdentifierException
   *   If no identifiers or algorithm are set.
   */
  public function getHashed();

  /**
   * Sets the pre-hashed identifier.
   *
   * @param string $string
   *   The hashed identifier.
   *
   * @return $this
   *   This object.
   */
  public function setHashed(string $string);

  /**
   * Sets the hashing algorithm.
   *
   * @param callable $callable
   *   The hashing algorithm.
   *
   * @return $this
   *   This object.
   */
  public function setHasher(callable $callable);

}
