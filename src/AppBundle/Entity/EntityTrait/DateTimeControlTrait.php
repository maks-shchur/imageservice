<?php

namespace AppBundle\Entity\EntityTrait;

trait DateTimeControlTrait
{
    /**
     * Executes automatically before inserting record
     * @return $this
     */
    public function setDateCreateValue()
    {
        $this->dateCreate = new \DateTime();

        return $this;
    }

    /**
     * Executes automatically before inserting/updating record
     * @return $this
     */
    public function setDateUpdateValue()
    {
        $this->dateUpdate = new \DateTime();

        return $this;
    }
}
