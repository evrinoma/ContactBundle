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

namespace Evrinoma\ContactBundle\Dto;

use Evrinoma\DtoBundle\Annotation\Dto;
use Evrinoma\DtoBundle\Dto\AbstractDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\BriefTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionTrait;
use Symfony\Component\HttpFoundation\Request;

class GroupApiDto extends AbstractDto implements GroupApiDtoInterface
{
    use ActiveTrait;
    use BriefTrait;
    use IdTrait;
    use PositionTrait;

    /**
     * @Dto(class="Evrinoma\ContactBundle\Dto\ContactApiDto", generator="genRequestContactApiDto")
     */
    private ?ContactApiDtoInterface $contactApiDto = null;

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

    public function hasContactApiDto(): bool
    {
        return null !== $this->contactApiDto;
    }

    public function getContactApiDto(): ContactApiDtoInterface
    {
        return $this->contactApiDto;
    }

    public function genRequestContactApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $type = $request->get(ContactApiDtoInterface::CONTACT);
            if ($type) {
                $newRequest = $this->getCloneRequest();
                $type[DtoInterface::DTO_CLASS] = ContactApiDto::class;
                $newRequest->request->add($type);

                yield $newRequest;
            }
        }
    }

    public function toDto(Request $request): DtoInterface
    {
        $class = $request->get(DtoInterface::DTO_CLASS);

        if ($class === $this->getClass()) {
            $id = $request->get(GroupApiDtoInterface::ID);
            $active = $request->get(GroupApiDtoInterface::ACTIVE);
            $brief = $request->get(GroupApiDtoInterface::BRIEF);
            $position = $request->get(GroupApiDtoInterface::POSITION);

            if ($brief) {
                $this->setBrief($brief);
            }
            if ($active) {
                $this->setActive($active);
            }
            if ($id) {
                $this->setId($id);
            }
            if ($position) {
                $this->setPosition($position);
            }
        }

        return $this;
    }
}
