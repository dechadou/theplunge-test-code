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
 * @ORM\Table(name="ec_metas")
 */
class Meta
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
     * @Gedmo\Slug(fields={"id","meta"},updatable=true)
     * @ORM\Column(unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=191, unique=false, nullable=false)
     */
    protected $meta;

    /**
     * @ORM\Column(type="text", unique=false, nullable=false)
     */
    protected $value;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getMeta();
    }

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
     * Set slug
     *
     * @param string $slug
     *
     * @return Meta
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set meta
     *
     * @param string $meta
     *
     * @return Meta
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get meta
     *
     * @return string
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Meta
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'slug' => $this->getSlug(),
            'meta' => $this->getMeta(),
            'value' => $this->getValue()
        ];
    }
}
