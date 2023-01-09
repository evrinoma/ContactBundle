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

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Preserve\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\BriefTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\PositionTrait;

trait GroupApiDtoTrait
{
    use ActiveTrait;
    use BriefTrait;
    use IdTrait;
    use PositionTrait;

    public function addContactApiDto(ContactApiDtoInterface $contactApiDto): DtoInterface
    {
        return parent::addContactApiDto($contactApiDto);
    }

    /**
     * @param ContactApiDtoInterface $contactApiDto
     *
     * @return DtoInterface
     */
    public function setContactApiDto(ContactApiDtoInterface $contactApiDto): DtoInterface
    {
        return parent::setContactApiDto($contactApiDto);
    }
}
