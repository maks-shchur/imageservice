<?php

namespace AppBundle\Entity\EntityManager;

use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\Image;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Repository\TagRepository;

class ImageManager
{
    /** @var  ObjectManager */
    private $objectManager;

    /**
     * CatalogManager constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function setTags(Image $image, $tags)
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
}
