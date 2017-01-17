<?php

namespace AppBundle\Entity;

use AppBundle\Entity\EntityTrait\DateTimeControlTrait;

/**
 * Tag
 */
class Tag
{
    use DateTimeControlTrait;
    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var \DateTime
     */
    private $dateCreate;

    /**
     * @var \DateTime
     */
    private $dateUpdate;

    /**
     * @var string
     */
    private $images;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return Tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Tag
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Tag
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set images
     *
     * @param string $images
     *
     * @return Tag
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get images
     *
     * @return string
     */
    public function getImages()
    {
        return $this->images;
    }
}
