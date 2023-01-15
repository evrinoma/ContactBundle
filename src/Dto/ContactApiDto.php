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

use Evrinoma\ContactBundle\DtoCommon\ValueObject\Mutable\GroupApiDtoTrait;
use Evrinoma\ContactBundle\DtoCommon\ValueObject\Mutable\GroupsApiDtoTrait;
use Evrinoma\DtoBundle\Annotation\Dto;
use Evrinoma\DtoBundle\Annotation\Dtos;
use Evrinoma\DtoBundle\Dto\AbstractDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\TitleTrait;
use Evrinoma\MailBundle\Dto\MailApiDtoInterface;
use Evrinoma\MailBundle\DtoCommon\ValueObject\Mutable\MailsApiDtoTrait;
use Evrinoma\PhoneBundle\Dto\PhoneApiDtoInterface;
use Evrinoma\PhoneBundle\DtoCommon\ValueObject\Mutable\PhonesApiDtoTrait;
use Symfony\Component\HttpFoundation\Request;

class ContactApiDto extends AbstractDto implements ContactApiDtoInterface
{
    use ActiveTrait;
    use GroupApiDtoTrait;
    use GroupsApiDtoTrait;
    use IdTrait;
    use MailsApiDtoTrait;
    use PhonesApiDtoTrait;
    use PositionTrait;
    use TitleTrait;

    /**
     * @Dto(class="Evrinoma\ContactBundle\Dto\GroupApiDto", generator="genRequestGroupApiDto")
     *
     * @var GroupApiDtoInterface|null
     */
    protected ?GroupApiDtoInterface $groupApiDto = null;

    /**
     * @Dtos(class="Evrinoma\MailBundle\Dto\MailApiDto", generator="genRequestMailsApiDto", add="addMailsApiDto")
     *
     * @var MailApiDtoInterface []
     */
    protected array $mailsApiDto = [];

    /**
     * @Dtos(class="Evrinoma\PhoneBundle\Dto\PhoneApiDto", generator="genRequestPhonesApiDto", add="addPhonesApiDto")
     *
     * @var PhoneApiDtoInterface []
     */
    protected array $phonesApiDto = [];

    /**
     * @Dtos(class="Evrinoma\ContactBundle\Dto\GroupApiDto", generator="genRequestGroupsApiDto", add="addGroupsApiDto")
     *
     * @var GroupApiDtoInterface []
     */
    protected array $groupsApiDto = [];

    public function toDto(Request $request): DtoInterface
    {
        $class = $request->get(DtoInterface::DTO_CLASS);

        if ($class === $this->getClass()) {
            $id = $request->get(ContactApiDtoInterface::ID);
            $active = $request->get(ContactApiDtoInterface::ACTIVE);
            $title = $request->get(ContactApiDtoInterface::TITLE);
            $position = $request->get(ContactApiDtoInterface::POSITION);

            if ($active) {
                $this->setActive($active);
            }
            if ($id) {
                $this->setId($id);
            }
            if ($position) {
                $this->setPosition($position);
            }
            if ($title) {
                $this->setTitle($title);
            }
        }

        return $this;
    }
}
