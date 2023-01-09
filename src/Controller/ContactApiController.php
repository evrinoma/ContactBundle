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

namespace Evrinoma\ContactBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Contact\ContactCannotBeSavedException;
use Evrinoma\ContactBundle\Exception\Contact\ContactInvalidException;
use Evrinoma\ContactBundle\Exception\Contact\ContactNotFoundException;
use Evrinoma\ContactBundle\Facade\Contact\FacadeInterface;
use Evrinoma\ContactBundle\Serializer\GroupInterface;
use Evrinoma\DtoBundle\Factory\FactoryDtoInterface;
use Evrinoma\UtilsBundle\Controller\AbstractWrappedApiController;
use Evrinoma\UtilsBundle\Controller\ApiControllerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ContactApiController extends AbstractWrappedApiController implements ApiControllerInterface
{
    private string $dtoClass;

    private ?Request $request;

    private FactoryDtoInterface $factoryDto;

    private FacadeInterface $facade;

    public function __construct(
        SerializerInterface $serializer,
        RequestStack $requestStack,
        FactoryDtoInterface $factoryDto,
        FacadeInterface $facade,
        string $dtoClass
    ) {
        parent::__construct($serializer);
        $this->request = $requestStack->getCurrentRequest();
        $this->factoryDto = $factoryDto;
        $this->dtoClass = $dtoClass;
        $this->facade = $facade;
    }

    /**
     * @Rest\Post("/api/contact/create", options={"expose": true}, name="api_contact_create")
     * @OA\Post(
     *     tags={"contact"},
     *     description="the method perform create contact",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\ContactBundle\Dto\ContactApiDto",
     *                     "position": "0",
     *                     "title": "bla bla",
     *                     "groups": {
     *                         {
     *                             "class": "Evrinoma\ContactBundle\Dto\GroupApiDto",
     *                             "id": "1",
     *                         },
     *                     }
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\ContactBundle\Dto\ContactApiDto"),
     *                 @OA\Property(property="position", type="string"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="groups", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\ContactBundle\Dto\GroupApiDto"),
     *                         @OA\Property(property="id", type="string", default="1"),
     *                     )
     *                 ),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Create contact")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var ContactApiDtoInterface $contactApiDto */
        $contactApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusCreated();

        $json = [];
        $error = [];
        $group = GroupInterface::API_POST_CONTACT;

        try {
            $this->facade->post($contactApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Create contact', $json, $error);
    }

    /**
     * @Rest\Put("/api/contact/save", options={"expose": true}, name="api_contact_save")
     * @OA\Put(
     *     tags={"contact"},
     *     description="the method perform save contact for current entity",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\ContactBundle\Dto\ContactApiDto",
     *                     "id": "1",
     *                     "active": "b",
     *                     "position": "0",
     *                     "title": "bla bla",
     *                     "groups": {
     *                         {
     *                             "class": "Evrinoma\ContactBundle\Dto\GroupApiDto",
     *                             "id": "1",
     *                         },
     *                     }
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\ContactBundle\Dto\ContactApiDto"),
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="active", type="string"),
     *                 @OA\Property(property="position", type="string"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="groups", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\ContactBundle\Dto\GroupApiDto"),
     *                         @OA\Property(property="id", type="string", default="1"),
     *                     )
     *                 ),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Save contact")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var ContactApiDtoInterface $contactApiDto */
        $contactApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_PUT_CONTACT;

        try {
            $this->facade->put($contactApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Save contact', $json, $error);
    }

    /**
     * @Rest\Delete("/api/contact/delete", options={"expose": true}, name="api_contact_delete")
     * @OA\Delete(
     *     tags={"contact"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ContactBundle\Dto\ContactApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Delete contact")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var ContactApiDtoInterface $contactApiDto */
        $contactApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusAccepted();

        $json = [];
        $error = [];

        try {
            $this->facade->delete($contactApiDto, '', $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->JsonResponse('Delete contact', $json, $error);
    }

    /**
     * @Rest\Get("/api/contact/criteria", options={"expose": true}, name="api_contact_criteria")
     * @OA\Get(
     *     tags={"contact"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ContactBundle\Dto\ContactApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="active",
     *         in="query",
     *         name="active",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="position",
     *         in="query",
     *         name="position",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="title",
     *         in="query",
     *         name="title",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="group[brief]",
     *         in="query",
     *         description="Group Contact",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 ref=@Model(type=Evrinoma\ContactBundle\Form\Rest\Group\GroupChoiceType::class, options={"data": "brief"})
     *             ),
     *         ),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="group[active]",
     *         in="query",
     *         description="Group active",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     * )
     * @OA\Response(response=200, description="Return contact")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var ContactApiDtoInterface $contactApiDto */
        $contactApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_CRITERIA_CONTACT;

        try {
            $this->facade->criteria($contactApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get contact', $json, $error);
    }

    /**
     * @Rest\Get("/api/contact", options={"expose": true}, name="api_contact")
     * @OA\Get(
     *     tags={"contact"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ContactBundle\Dto\ContactApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Return contact")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var ContactApiDtoInterface $contactApiDto */
        $contactApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_GET_CONTACT;

        try {
            $this->facade->get($contactApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get contact', $json, $error);
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    public function setRestStatus(\Exception $e): array
    {
        switch (true) {
            case $e instanceof ContactCannotBeSavedException:
                $this->setStatusNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $this->setStatusConflict();
                break;
            case $e instanceof ContactNotFoundException:
                $this->setStatusNotFound();
                break;
            case $e instanceof ContactInvalidException:
                $this->setStatusUnprocessableEntity();
                break;
            default:
                $this->setStatusBadRequest();
        }

        return [$e->getMessage()];
    }
}
