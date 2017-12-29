<?php

namespace dpi\ak_test\AvatarKit\AvatarServices;

use dpi\ak\AvatarIdentifierInterface;
use dpi\ak\AvatarKit\AvatarServices\AvatarServiceBase;

/**
 * Mystery Man avatar service.
 *
 * @AvatarService(
 *   id = "ak_fake",
 *   name = "Fake",
 *   description = "A fake avatar service.",
 *   protocols = {
 *     "https"
 *   },
 *   dimensions = "64x64-128x128",
 *   is_dynamic = FALSE,
 *   is_fallback = TRUE,
 *   is_remote = TRUE
 * )
 */
class FakeAvatarService extends AvatarServiceBase {

  /**
   * {@inheritdoc}
   */
  public function getAvatar(AvatarIdentifierInterface $identifier): ?string {
    return '';
  }

}
