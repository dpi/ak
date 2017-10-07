<?php

declare(strict_types = 1);

namespace dpi\ak;

/**
 * Avatar configuration.
 */
class AvatarConfiguration implements AvatarConfigurationInterface {

  /**
   * The width of avatars.
   *
   * @var int|null
   */
  protected $width = NULL;

  /**
   * The height of avatars.
   *
   * @var int|null
   */
  protected $height = NULL;

  /**
   * The protocol of avatars.
   *
   * @var string|null
   */
  protected $protocol = NULL;

  /**
   * {@inheritdoc}
   */
  public function getWidth() : ?int {
    return $this->width;
  }

  /**
   * {@inheritdoc}
   */
  public function setWidth(int $width) {
    $this->width = $width;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHeight() : ?int {
    return $this->height;
  }

  /**
   * {@inheritdoc}
   */
  public function setHeight(int $height) {
    $this->height = $height;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProtocol() : ?string {
    return $this->protocol;
  }

  /**
   * {@inheritdoc}
   */
  public function setProtocol(string $protocol) {
    $this->protocol = $protocol;
    return $this;
  }

}
