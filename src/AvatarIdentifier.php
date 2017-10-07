<?php

declare(strict_types = 1);

namespace dpi\ak;

use dpi\ak\Exception\AvatarIdentifierException;

/**
 * The avatar identifier.
 */
class AvatarIdentifier implements AvatarIdentifierInterface {

  /**
   * The raw identifier.
   *
   * @var string|null
   */
  protected $raw = NULL;

  /**
   * The pre-hashed identifier.
   *
   * @var string|null
   */
  protected $hashed = NULL;

  /**
   * A one way hashing algorithm.
   *
   * @var callable|null
   */
  protected $hasher = NULL;

  /**
   * {@inheritdoc}
   */
  public function getRaw() {
    if (isset($this->raw)) {
      return $this->raw;
    }
    throw new AvatarIdentifierException('No raw value set. The value was either not set, or only the hashed value is available.');
  }

  /**
   * {@inheritdoc}
   */
  public function setRaw(string $raw) {
    $this->raw = $raw;
    $this->hashed = NULL;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHashed() {
    if (isset($this->hashed)) {
      return $this->hashed;
    }

    $hasher = $this->hasher ?? NULL;
    $raw = $this->raw ?? NULL;
    if ($hasher && $raw) {
      if (!is_callable($hasher)) {
        throw new AvatarIdentifierException('No hashing algorithm set.');
      }
      return call_user_func($hasher, $raw);
    }
    else {
      throw new AvatarIdentifierException('No values set.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setHashed(string $string) {
    $this->raw = NULL;
    $this->hashed = $string;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setHasher(callable $callable) {
    $this->hasher = $callable;
    return $this;
  }

}
