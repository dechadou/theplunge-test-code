<?php

namespace App\CoreBundle\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use MediaMonks\Doctrine\Mapping\Annotation as MediaMonks;

trait CreatedByEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $created_by;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="updated_by_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $updated_by;

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @param mixed $created_by
     * @return CreatedByEntity
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * @param mixed $updated_by
     * @return CreatedByEntity
     */
    public function setUpdatedBy($updated_by)
    {
        $this->updated_by = $updated_by;
        return $this;
    }
}
