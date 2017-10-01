<?php

declare(strict_types = 1);

namespace dpi\ak;

/**
 * Avatar configuration interface.
 */
interface AvatarConfigurationInterface {

  /**
   * Get the configured width.
   *
   * @return int|null
   *   The width of the avatar, or null if not set.
   */
  public function getWidth() : ?int;

  /**
   * Set the width.
   *
   * @param int $width
   *   Sets the width.
   *
   * @return $this
   *   This object.
   */
  public function setWidth(int $width);

  /**
   * Get the configured height.
   *
   * @return int|null
   *   The height of the avatar, or null if not set.
   */
  public function getHeight() : ?int;

  /**
   * Set the height.
   *
   * @param int $height
   *   Sets the height.
   *
   * @return $this
   *   This object.
   */
  public function setHeight(int $height);

  /**
   * Get the configured protocol.
   *
   * @return string|null
   *   The requested protocol, or null if not set.
   */
  public function getProtocol() : ?string;

  /**
   * Set the protocol.
   *
   * @param string $protocol
   *   Sets the protocol.
   *
   * @return $this
   *   This object.
   */
  public function setProtocol(string $protocol);

}
