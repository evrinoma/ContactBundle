<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * (c) Nikolay Nikolaev <evrinoma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evrinoma\ContactBundle\Dto\Preserve;

use Evrinoma\ContactBundle\DtoCommon\ValueObject\Preserve\GroupsTrait;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Preserve\GroupTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\PositionTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\TitleTrait;

trait ContactApiDtoTrait
{
    use ActiveTrait;
    use GroupsTrait;
    use GroupTrait;
    use IdTrait;
    use PositionTrait;
    use TitleTrait;
}
