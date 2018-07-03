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
 * @ORM\Table(name="ec_tipos_productos")
 */
class TipoProducto
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
     * @ORM\Column(type="string", length=191,unique=true, nullable=false)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Producto", mappedBy="tipo_producto", cascade={"all"}, orphanRemoval=true)
     */
    protected $producto;

    /**
     * @Gedmo\Slug(fields={"id","name"},updatable=true)
     * @ORM\Column(unique=true)
     */
    private $slug;

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()
        ];
    }

    public function __construct()
    {
        $this->producto = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return TipoProducto
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return TipoProducto
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return TipoProducto
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;
        return $this;
    }

    /**
     * @param $element
     * @return $this
     */
    public function addProducto($element)
    {
        $this->producto->add($element);
        return $this;
    }

    /**
     * @param $element
     * @return $this
     */
    public function removeProducto($element)
    {
        $this->producto->removeElement($element);
        return $this;
    }


    /**
     * @return mixed
     */
    public function __toString()
    {
        return ($this->getName() != '') ? $this->getName() : '';
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return TipoProducto
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }



}
