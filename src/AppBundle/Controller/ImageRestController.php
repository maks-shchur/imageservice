<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Repository\ImageRepository;
use AppBundle\Services\Files;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use AppBundle\Entity\Image;
use AppBundle\Entity\EntityManager\ImageManager;

class ImageRestController extends FOSRestController
{
    /**
     * Create image
     *
     * ## Response OK ##
     *     {
     *          "id": (int)"",
     *          "link": (string)"",
     *          "tags": (array)[
     *              (dictionary){
     *                  "id": (int)"",
     *                  "tag": (string)""
     *              },
     *              ...
     *          ]
     *     }
     *
     * @ApiDoc(
     *   section = "Image",
     *   resource = true,
     *   description = "Create image",
     *   parameters={
     *       {"name"="imageFile", "dataType"="file", "required"=true, "description"="File image"},
     *       {"name"="tags", "dataType"="string", "required"=false, "description"="Tags list"}
     *   },
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @Rest\Post("/images")
     *
     * @param Request $request
     * @return array
     */
    public function postImagesAction(Request $request)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->get("app.entity_manager");
        /** @var Files $filesService */
        $filesService = $this->get("app.files");
        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');
        /** @var Router $router */
        $router = $this->get('router');
        /** @var Image $image */
        $image = new Image();

        /* @var UploadedFile $imageFile */
        $imageFile = $request->files->get("imageFile");
        $imageNameName = $filesService->saveFileWithRandomName($imageFile, '/uploads');
        $image->setLink('http://' . $router->getContext()->getHost() . '/uploads/' . $imageNameName);

        $params = $request->request->all();
        if (isset($params['tags']) and $params['tags']) {
            $tags = explode(' ', $params['tags']);
            /** @var ImageManager $imageManager */
            $imageManager = $this->get("app.image.manager");
            $image = $imageManager->addTags($image, $tags);
        }

        $entityManager->persist($image);
        $entityManager->flush();

        return $serializer->toArray(
            $image,
            SerializationContext::create()->setGroups(array('short'))
        );
    }

    /**
     * Delete image
     *
     * ## Response OK ##
     *     true
     *
     * ### Response FAIL image not found ###
     *
     *     {
     *          "code": 404,
     *          "message": "Image not found"
     *     }
     *
     * @ApiDoc(
     *   section = "Image",
     *   resource = true,
     *   description = "Delete image",
     *   parameters={
     *       {"name"="id", "dataType"="integer", "required"=true, "description"="Image ID"}
     *   },
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Image not found"
     *   }
     * )
     *
     * @Rest\Delete("/images")
     *
     * @param Request $request
     * @return boolean
     */
    public function deleteImagesAction(Request $request)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->get("app.entity_manager");
        /** @var ImageRepository $imageRepository */
        $imageRepository = $entityManager->getRepository('AppBundle:Image');
        $params = $request->request->all();

        /** @var Image $image */
        $image = $imageRepository->find($params['id']);
        if (!$image) {
            throw new HttpException(404, "Image not found");
        }

        /** @var ImageManager $imageManager */
        $imageManager = $this->get("app.image.manager");
        $imageManager->delete($image);
        $entityManager->flush();

        return true;
    }

    /**
     * Add tags for image
     *
     * ## Response OK ##
     *     {
     *          "id": (int)"",
     *          "link": (string)"",
     *          "tags": (array)[
     *              (dictionary){
     *                  "id": (int)"",
     *                  "tag": (string)""
     *              },
     *              ...
     *          ]
     *     }
     *
     * ### Response FAIL image not found ###
     *
     *     {
     *          "code": 404,
     *          "message": "Image not found"
     *     }
     *
     * @ApiDoc(
     *   section = "Image",
     *   resource = true,
     *   description = "Add tags for image",
     *   parameters={
     *       {"name"="id", "dataType"="integer", "required"=true, "description"="Image ID"},
     *       {"name"="tags", "dataType"="string", "required"=true, "description"="Tags list"}
     *   },
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Image not found"
     *   }
     * )
     *
     * @Rest\Put("/images/add-tags")
     *
     * @param Request $request
     * @return array
     */
    public function putImagesAddTagsAction(Request $request)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->get("app.entity_manager");
        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');
        /** @var ImageRepository $imageRepository */
        $imageRepository = $entityManager->getRepository('AppBundle:Image');
        /** @var ImageManager $imageManager */
        $imageManager = $this->get("app.image.manager");
        $params = $request->request->all();

        /** @var Image $image */
        $image = $imageRepository->find($params['id']);
        if (!$image) {
            throw new HttpException(404, "Image not found");
        }

        $tags = explode(' ', $params['tags']);
        $image = $imageManager->addTags($image, $tags);
        $entityManager->persist($image);
        $entityManager->flush();

        return $serializer->toArray(
            $image,
            SerializationContext::create()->setGroups(array('short'))
        );
    }

    /**
     * Delete tags for image
     *
     * ## Response OK ##
     *     {
     *          "id": (int)"",
     *          "link": (string)"",
     *          "tags": (array)[
     *              (dictionary){
     *                  "id": (int)"",
     *                  "tag": (string)""
     *              },
     *              ...
     *          ]
     *     }
     *
     * ### Response FAIL image not found ###
     *
     *     {
     *          "code": 404,
     *          "message": "Image not found"
     *     }
     *
     * @ApiDoc(
     *   section = "Image",
     *   resource = true,
     *   description = "Delete tags for image",
     *   parameters={
     *       {"name"="id", "dataType"="integer", "required"=true, "description"="Image ID"},
     *       {"name"="tags", "dataType"="string", "required"=true, "description"="Tags list"}
     *   },
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Image not found"
     *   }
     * )
     *
     * @Rest\Put("/images/delete-tags")
     *
     * @param Request $request
     * @return array
     */
    public function putImagesDeleteTagsAction(Request $request)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->get("app.entity_manager");
        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');
        /** @var ImageRepository $imageRepository */
        $imageRepository = $entityManager->getRepository('AppBundle:Image');
        /** @var ImageManager $imageManager */
        $imageManager = $this->get("app.image.manager");
        $params = $request->request->all();

        /** @var Image $image */
        $image = $imageRepository->find($params['id']);
        if (!$image) {
            throw new HttpException(404, "Image not found");
        }

        $tags = explode(' ', $params['tags']);
        $image = $imageManager->deleteTags($image, $tags);
        $entityManager->persist($image);
        $entityManager->flush();

        return $serializer->toArray(
            $image,
            SerializationContext::create()->setGroups(array('short'))
        );
    }
}
