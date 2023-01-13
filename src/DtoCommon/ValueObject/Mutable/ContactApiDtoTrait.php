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

namespace Evrinoma\ContactBundle\DtoCommon\ValueObject\Mutable;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable\ContactApiDtoTrait as ContactApiDtoImmutableTrait;
use Evrinoma\DtoBundle\Dto\DtoInterface;

trait ContactApiDtoTrait
{
    use ContactApiDtoImmutableTrait;

    /**
     * @param ContactApiDtoInterface $contactApiDto
     *
     * @return DtoInterface
     */
    public function setContactApiDto(ContactApiDtoInterface $contactApiDto): DtoInterface
    {
        $this->contactApiDto = $contactApiDto;

        return $this;
    }
}
