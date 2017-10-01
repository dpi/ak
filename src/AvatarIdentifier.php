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
  protected $raw;

  /**
   * The pre-hashed identifier.
   *
   * @var string|null
   */
  protected $hashed;

  /**
   * A one way hashing algorithm.
   *
   * @var callable
   */
  protected $hasher;

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
    elseif (isset($this->raw)) {
      if (!is_callable($this->hasher)) {
        throw new AvatarIdentifierException('No hashing algorithm set.');
      }
      $raw = $this->raw;
      return call_user_func($this->hasher, $raw);
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
