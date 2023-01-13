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

use Evrinoma\AddressBundle\DtoCommon\ValueObject\Preserve\AddressApiDtoTrait;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Preserve\ContactApiDtoTrait;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Preserve\ContactsApiDtoTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\BriefTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\PositionTrait;

trait GroupApiDtoTrait
{
    use ActiveTrait;
    use AddressApiDtoTrait;
    use BriefTrait;
    use ContactApiDtoTrait;
    use ContactsApiDtoTrait;
    use IdTrait;
    use PositionTrait;
}
