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
 * @ORM\Table(name="ec_productos_media")
 */
class ProductoMedia
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
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Producto", inversedBy="media", cascade={"persist"})
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $producto;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $primary_media;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     */
    public $media;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ProductoMedia
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * @param mixed $producto
     * @return ProductoMedia
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimaryMedia()
    {
        return $this->primary_media;
    }

    /**
     * @param mixed $primary_media
     * @return ProductoMedia
     */
    public function setPrimaryMedia($primary_media)
    {
        $this->primary_media = $primary_media;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     * @return ProductoMedia
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

}
