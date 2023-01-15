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
use Evrinoma\ContactBundle\Entity\Contact\BaseContact;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;
use Evrinoma\PhoneBundle\Fixtures\PhoneFixtures;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class ContactFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            ContactApiDtoInterface::TITLE => 'ite',
            ContactApiDtoInterface::POSITION => 1,
            ContactApiDtoInterface::ACTIVE => 'a',
            ContactApiDtoInterface::PHONES => [0, 1],
        ],
        [
            ContactApiDtoInterface::TITLE => 'kzkt',
            ContactApiDtoInterface::POSITION => 2,
            ContactApiDtoInterface::ACTIVE => 'a',
            ContactApiDtoInterface::PHONES => [2, 3],
        ],
        [
            ContactApiDtoInterface::TITLE => 'c2m',
            ContactApiDtoInterface::POSITION => 3,
            ContactApiDtoInterface::ACTIVE => 'a',
            ContactApiDtoInterface::PHONES => [4, 5],
        ],
        [
            ContactApiDtoInterface::TITLE => 'kzkt2',
            ContactApiDtoInterface::POSITION => 1,
            ContactApiDtoInterface::ACTIVE => 'd',
            ContactApiDtoInterface::PHONES => [6, 7],
        ],
        [
            ContactApiDtoInterface::TITLE => 'nvr',
            ContactApiDtoInterface::POSITION => 2,
            ContactApiDtoInterface::ACTIVE => 'b',
            ContactApiDtoInterface::PHONES => [0, 1],
        ],
        [
            ContactApiDtoInterface::TITLE => 'nvr2',
            ContactApiDtoInterface::POSITION => 3,
            ContactApiDtoInterface::ACTIVE => 'd',
            ContactApiDtoInterface::PHONES => [2, 3],
        ],
        [
            ContactApiDtoInterface::TITLE => 'nvr3',
            ContactApiDtoInterface::POSITION => 1,
            ContactApiDtoInterface::ACTIVE => 'd',
        ],
    ];

    protected static string $class = BaseContact::class;

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
        $shortPhone = PhoneFixtures::getReferenceName();
        $i = 0;

        foreach (static::$data as $record) {
            /** @var ContactInterface $entity */
            $entity = new static::$class();
            $entity
                ->setActive($record[ContactApiDtoInterface::ACTIVE])
                ->setTitle($record[ContactApiDtoInterface::TITLE])
                ->setPosition($record[ContactApiDtoInterface::POSITION])
                ->setCreatedAt(new \DateTimeImmutable())
            ;

            if (\array_key_exists(ContactApiDtoInterface::PHONES, $record)) {
                foreach ($record[ContactApiDtoInterface::PHONES] as $value) {
                    $entity
                        ->addPhone($this->getReference($shortPhone.$value));
                }
            }

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
        return 0;
    }
}
