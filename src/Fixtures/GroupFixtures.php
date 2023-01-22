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

namespace Evrinoma\ContactBundle\Fixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Evrinoma\AddressBundle\Fixtures\AddressFixtures;
use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Entity\Group\BaseGroup;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class GroupFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            GroupApiDtoInterface::BRIEF => 'ite',
            GroupApiDtoInterface::ACTIVE => 'a',
            GroupApiDtoInterface::POSITION => 1,
            GroupApiDtoInterface::CONTACTS => [4, 3],
            GroupApiDtoInterface::ADDRESS => 1,
        ],
        [
            GroupApiDtoInterface::BRIEF => 'kzkt',
            GroupApiDtoInterface::ACTIVE => 'a',
            GroupApiDtoInterface::POSITION => 2,
            GroupApiDtoInterface::CONTACTS => [1, 2],
            GroupApiDtoInterface::ADDRESS => 2,
        ],
        [
            GroupApiDtoInterface::BRIEF => 'c2m',
            GroupApiDtoInterface::ACTIVE => 'a',
            GroupApiDtoInterface::POSITION => 3,
            GroupApiDtoInterface::CONTACTS => [0, 3],
        ],
        [
            GroupApiDtoInterface::BRIEF => 'kzkt2',
            GroupApiDtoInterface::ACTIVE => 'd',
            GroupApiDtoInterface::POSITION => 4,
            GroupApiDtoInterface::CONTACTS => [1, 2],
            GroupApiDtoInterface::ADDRESS => 3,
            ],
        [
            GroupApiDtoInterface::BRIEF => 'nvr',
            GroupApiDtoInterface::ACTIVE => 'b',
            GroupApiDtoInterface::POSITION => 5,
            GroupApiDtoInterface::CONTACTS => [0, 3],
            GroupApiDtoInterface::ADDRESS => 4,
        ],
        [
            GroupApiDtoInterface::BRIEF => 'nvr2',
            GroupApiDtoInterface::ACTIVE => 'd',
            GroupApiDtoInterface::POSITION => 6,
            GroupApiDtoInterface::CONTACTS => [1, 2],
            GroupApiDtoInterface::ADDRESS => 5,
            ],
        [
            GroupApiDtoInterface::BRIEF => 'nvr3',
            GroupApiDtoInterface::ACTIVE => 'd',
            GroupApiDtoInterface::POSITION => 7,
            GroupApiDtoInterface::CONTACTS => [2, 4],
        ],
        [
            GroupApiDtoInterface::BRIEF => 'tvr',
            GroupApiDtoInterface::ACTIVE => 'b',
            GroupApiDtoInterface::POSITION => 5,
            GroupApiDtoInterface::CONTACTS => [0, 3],
            GroupApiDtoInterface::ADDRESS => 6,
        ],
        [
            GroupApiDtoInterface::BRIEF => 'tvr2',
            GroupApiDtoInterface::ACTIVE => 'd',
            GroupApiDtoInterface::POSITION => 6,
            GroupApiDtoInterface::CONTACTS => [1, 2],
            GroupApiDtoInterface::ADDRESS => 0,
        ],
        [
            GroupApiDtoInterface::BRIEF => 'tvr3',
            GroupApiDtoInterface::ACTIVE => 'd',
            GroupApiDtoInterface::POSITION => 7,
            GroupApiDtoInterface::CONTACTS => [2, 4],
            GroupApiDtoInterface::ADDRESS => 7,
        ],
    ];

    protected static string $class = BaseGroup::class;

    /**
     * @param ObjectManager $manager
     *
     * @return $this
     *
     * @throws \Exception
     */
    protected function create(ObjectManager $manager): self
    {
        $short = self::getReferenceName();
        $shortContact = ContactFixtures::getReferenceName();
        $shortAddress = AddressFixtures::getReferenceName();
        $i = 0;

        foreach ($this->getData() as $record) {
            $entity = $this->getEntity();
            $entity
                ->setActive($record[GroupApiDtoInterface::ACTIVE])
                ->setBrief($record[GroupApiDtoInterface::BRIEF])
                ->setPosition($record[GroupApiDtoInterface::POSITION])
                ->setCreatedAt(new \DateTimeImmutable())
            ;

            foreach ($record[GroupApiDtoInterface::CONTACTS] as $value) {
                $entity
                    ->addContact($this->getReference($shortContact.$value));
            }

            if (\array_key_exists(GroupApiDtoInterface::ADDRESS, $record)) {
                $entity
                    ->setAddress($this->getReference($shortAddress.$record[GroupApiDtoInterface::ADDRESS]));
            }

            $this->expandEntity($entity);

            $this->addReference($short.$i, $entity);
            $manager->persist($entity);
            ++$i;
        }

        return $this;
    }

    public static function getGroups(): array
    {
        return [
            FixtureInterface::CONTACT_FIXTURES, FixtureInterface::GROUP_FIXTURES,
        ];
    }

    public function getOrder()
    {
        return 100;
    }
}
