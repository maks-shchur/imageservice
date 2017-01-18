<?php

namespace AppBundle\Entity\EntityManager;

use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\Image;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Repository\TagRepository;
use Symfony\Component\Filesystem\Filesystem;

class ImageManager
{
    /** @var  ObjectManager */
    private $objectManager;
    private $rootDir;

    /**
     * ImageManager constructor.
     * @param ObjectManager $objectManager
     * @param $rootDir
     */
    public function __construct(ObjectManager $objectManager, $rootDir)
    {
        $this->objectManager = $objectManager;
        $this->rootDir = $rootDir;
    }

    /**
     * @param Image $image
     * @param $tags
     * @return Image
     */
    public function addTags(Image $image, $tags)
    {
        /** @var TagRepository $tagRepository */
        $tagRepository = $this->objectManager->getRepository('AppBundle:Tag');

        foreach ($tags as $tag) {
            $setTag = $tagRepository->findOneBy(['tag' => $tag]);

            if (!$setTag) {
                $setTag = new Tag();
                $setTag->setTag($tag);
                $this->objectManager->persist($setTag);
            }
            $image->addTag($setTag);
        }

        return $image;
    }

    /**
     * @param Image $image
     */
    public function delete(Image $image)
    {
        $this->objectManager->remove($image);
        $imageUrlSection = explode('/', $image->getLink());
        $imageName = end($imageUrlSection);
        $fs = new Filesystem();
        $fs->remove($this->rootDir . '/../web/uploads/' . $imageName);
    }
}
