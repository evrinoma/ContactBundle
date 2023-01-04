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
use Evrinoma\ContactBundle\Dto\FileApiDtoInterface;
use Evrinoma\ContactBundle\Exception\File\FileCannotBeSavedException;
use Evrinoma\ContactBundle\Exception\File\FileInvalidException;
use Evrinoma\ContactBundle\Exception\File\FileNotFoundException;
use Evrinoma\ContactBundle\Facade\File\FacadeInterface;
use Evrinoma\ContactBundle\Serializer\GroupInterface;
use Evrinoma\DtoBundle\Factory\FactoryDtoInterface;
use Evrinoma\UtilsBundle\Controller\AbstractWrappedApiController;
use Evrinoma\UtilsBundle\Controller\ApiControllerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class FileApiController extends AbstractWrappedApiController implements ApiControllerInterface
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
     * @Rest\Post("/api/contact/file/create", options={"expose": true}, name="api_contact_file_create")
     * @OA\Post(
     *     tags={"contact"},
     *     description="the method perform create contact type",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\ContactBundle\Dto\FileApiDto",
     *                     "position": "0",
     *                     "title": "bla bla",
     *                     "contact": {
     *                         "class": "Evrinoma\ContactBundle\Dto\ContactApiDto",
     *                         "id": "1",
     *                     }
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\ContactBundle\Dto\FileApiDto"),
     *                 @OA\Property(property="position", type="int"),
     *                 @OA\Property(property="brief", type="string"),
     *                 @OA\Property(property="contact", type="object",
     *                     @OA\Property(property="class", type="string", default="Evrinoma\ContactBundle\Dto\ContactApiDto"),
     *                     @OA\Property(property="id", type="string", default="1"),
     *                 ),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Create contact file")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusCreated();

        $json = [];
        $error = [];
        $group = GroupInterface::API_POST_CONTACT_FILE;

        try {
            $this->facade->post($fileApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Create contact file', $json, $error);
    }

    /**
     * @Rest\Put("/api/contact/file/save", options={"expose": true}, name="api_contact_file_save")
     * @OA\Put(
     *     tags={"contact"},
     *     description="the method perform save contact file for current entity",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\ContactBundle\Dto\FileApiDto",
     *                     "position": "0",
     *                     "id": "1",
     *                     "active": "b",
     *                     "title": "bla bla",
     *                     "contact": {
     *                         "class": "Evrinoma\ContactBundle\Dto\ContactApiDto",
     *                         "id": "1",
     *                     }
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\ContactBundle\Dto\FileApiDto"),
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="active", type="string"),
     *                 @OA\Property(property="position", type="int"),
     *                 @OA\Property(property="brief", type="string"),
     *                 @OA\Property(property="contact", type="object",
     *                     @OA\Property(property="class", type="string", default="Evrinoma\ContactBundle\Dto\ContactApiDto"),
     *                     @OA\Property(property="id", type="string", default="1"),
     *                 ),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Save contact file")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_PUT_CONTACT_FILE;

        try {
            $this->facade->put($fileApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Save contact file', $json, $error);
    }

    /**
     * @Rest\Delete("/api/contact/file/delete", options={"expose": true}, name="api_contact_file_delete")
     * @OA\Delete(
     *     tags={"contact"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ContactBundle\Dto\FileApiDto",
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
     * @OA\Response(response=200, description="Delete contact file")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusAccepted();

        $json = [];
        $error = [];

        try {
            $this->facade->delete($fileApiDto, '', $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->JsonResponse('Delete contact file', $json, $error);
    }

    /**
     * @Rest\Get("/api/contact/file/criteria", options={"expose": true}, name="api_contact_file_criteria")
     * @OA\Get(
     *     tags={"contact"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ContactBundle\Dto\FileApiDto",
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
     *         description="brief",
     *         in="query",
     *         name="brief",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Return contact file")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_CRITERIA_CONTACT_FILE;

        try {
            $this->facade->criteria($fileApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get contact file', $json, $error);
    }

    /**
     * @Rest\Get("/api/contact/file", options={"expose": true}, name="api_contact_file")
     * @OA\Get(
     *     tags={"contact"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\ContactBundle\Dto\FileApiDto",
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
     * @OA\Response(response=200, description="Return contact file")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_GET_CONTACT_FILE;

        try {
            $this->facade->get($fileApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get contact file', $json, $error);
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    public function setRestStatus(\Exception $e): array
    {
        switch (true) {
            case $e instanceof FileCannotBeSavedException:
                $this->setStatusNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $this->setStatusConflict();
                break;
            case $e instanceof FileNotFoundException:
                $this->setStatusNotFound();
                break;
            case $e instanceof FileInvalidException:
                $this->setStatusUnprocessableEntity();
                break;
            default:
                $this->setStatusBadRequest();
        }

        return [$e->getMessage()];
    }
}
