<?php

namespace App\CoreBundle\Entity;

use App\CoreBundle\Entity\Common\CreatedByEntity;
use App\CoreBundle\Entity\Common\PublishableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use MediaMonks\Doctrine\Mapping\Annotation as MediaMonks;

/**
 * @ORM\Entity
 * @ORM\Table(name="ec_combo_media")
 */
class ComboMedia
{
    use TimestampableEntity;
    use PublishableEntity;
    use CreatedByEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Combo", inversedBy="media", cascade={"persist"})
     * @ORM\JoinColumn(name="combo_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $combo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $primary_media;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     */
    public $media;


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
     * Set combo
     *
     * @param \App\CoreBundle\Entity\Combo $combo
     *
     * @return ComboMedia
     */
    public function setCombo(\App\CoreBundle\Entity\Combo $combo = null)
    {
        $this->combo = $combo;

        return $this;
    }

    /**
     * Get combo
     *
     * @return \App\CoreBundle\Entity\Combo
     */
    public function getCombo()
    {
        return $this->combo;
    }

    /**
     * Set media
     *
     * @param \App\CoreBundle\Entity\Media $media
     *
     * @return ComboMedia
     */
    public function setMedia(\App\CoreBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \App\CoreBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }


    /**
     * Set primaryMedia
     *
     * @param boolean $primaryMedia
     *
     * @return ComboMedia
     */
    public function setPrimaryMedia($primaryMedia)
    {
        $this->primary_media = $primaryMedia;

        return $this;
    }

    /**
     * Get primaryMedia
     *
     * @return boolean
     */
    public function getPrimaryMedia()
    {
        return $this->primary_media;
    }
}
