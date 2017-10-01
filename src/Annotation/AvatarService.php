<?php

// @codingStandardsIgnoreFile
// Coding standard does not like that properties are not camelCase.

declare(strict_types = 1);

namespace dpi\ak\Annotation;

/**
 * Annotation for avatar service providers.
 *
 * @Annotation
 */
class AvatarService {

  /**
   * An unique identifier for this service.
   *
   * @var string
   * @Required
   */
  public $id;

  /**
   * The friendly name for the service.
   *
   * @var string
   * @Required
   */
  public $name;

  /**
   * The friendly name for the service.
   *
   * @var string
   */
  public $description = '';

  /**
   * Protocols.
   *
   * @var string[]
   */
  public $protocols = [];

  /**
   * Mimes.
   *
   * @var string[]
   */
  public $mime = [];

  /**
   * Dimensions.
   *
   * Dimensions in format: AxB-YxZ. Where A and Y are minimum/maximum widths,
   * and where B and Z are minimum/maximum heights.
   *
   * Leave NULL if the service returns vector graphics without configurable
   * width/height.
   *
   * @var string
   */
  public $dimensions;

  /**
   * Is Dynamic.
   *
   * Can this service produce different avatars given idential input parameters?
   *
   * @var bool
   */
  public $is_dynamic = FALSE;

  /**
   * Is Fallback.
   *
   * Given appropriate configuration, will this service always produce an
   * avatar?
   *
   * @var bool
   */
  public $is_fallback = FALSE;

  /**
   * Is remote.
   *
   * Whether this plugin returns remote resources. If remote, then the resource
   * is created on the request (on demand). If not remote, then the file is
   * created locally. The file is only available on this local server.
   *
   * @var bool
   * @Required
   */
  public $is_remote;

}
