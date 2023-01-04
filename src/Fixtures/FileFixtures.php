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
use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Dto\FileApiDtoInterface;
use Evrinoma\ContactBundle\Entity\File\BaseFile;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class FileFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            FileApiDtoInterface::BRIEF => 'ite',
            FileApiDtoInterface::ACTIVE => 'a',
            FileApiDtoInterface::POSITION => 1,
            ContactApiDtoInterface::CONTACT => 0,
        ],
        [
            FileApiDtoInterface::BRIEF => 'kzkt',
            FileApiDtoInterface::ACTIVE => 'a',
            FileApiDtoInterface::POSITION => 2,
            ContactApiDtoInterface::CONTACT => 1,
        ],
        [
            FileApiDtoInterface::BRIEF => 'c2m',
            FileApiDtoInterface::ACTIVE => 'a',
            FileApiDtoInterface::POSITION => 3,
            ContactApiDtoInterface::CONTACT => 0,
        ],
        [
            FileApiDtoInterface::BRIEF => 'kzkt2',
            FileApiDtoInterface::ACTIVE => 'd',
            FileApiDtoInterface::POSITION => 4,
            ContactApiDtoInterface::CONTACT => 1,
            ],
        [
            FileApiDtoInterface::BRIEF => 'nvr',
            FileApiDtoInterface::ACTIVE => 'b',
            FileApiDtoInterface::POSITION => 5,
            ContactApiDtoInterface::CONTACT => 0,
        ],
        [
            FileApiDtoInterface::BRIEF => 'nvr2',
            FileApiDtoInterface::ACTIVE => 'd',
            FileApiDtoInterface::POSITION => 6,
            ContactApiDtoInterface::CONTACT => 1,
            ],
        [
            FileApiDtoInterface::BRIEF => 'nvr3',
            FileApiDtoInterface::ACTIVE => 'd',
            FileApiDtoInterface::POSITION => 7,
            ContactApiDtoInterface::CONTACT => 2,
        ],
    ];

    protected static string $class = BaseFile::class;

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
        $i = 0;

        foreach (static::$data as $record) {
            $entity = new static::$class();
            $entity
                ->setContact($this->getReference($shortContact.$record[ContactApiDtoInterface::CONTACT]))
                ->setActive($record[FileApiDtoInterface::ACTIVE])
                ->setBrief($record[FileApiDtoInterface::BRIEF])
                ->setPosition($record[FileApiDtoInterface::POSITION])
                ->setCreatedAt(new \DateTimeImmutable())
            ;

            $this->addReference($short.$i, $entity);
            $manager->persist($entity);
            ++$i;
        }

        return $this;
    }

    public static function getGroups(): array
    {
        return [
            FixtureInterface::CONTACT_FIXTURES, FixtureInterface::FILE_FIXTURES,
        ];
    }

    public function getOrder()
    {
        return 100;
    }
}
