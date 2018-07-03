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
 * @ORM\Table(name="ec_categorias")
 */
class Categoria
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
     * @ORM\Column(type="string", length=191, unique=true, nullable=false)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Tienda", inversedBy="categoria")
     */
    protected $tienda;

    /**
     * @ORM\ManyToMany(targetEntity="App\CoreBundle\Entity\Producto", mappedBy="categoria")
     */
    protected $producto;



    /**
     * @return mixed
     */
    public function __toString()
    {
        return ($this->getName() != '') ? $this->getName() : '';
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
     * Set name
     *
     * @param string $name
     *
     * @return Categoria
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tienda
     *
     * @param \App\CoreBundle\Entity\Tienda $tienda
     *
     * @return Categoria
     */
    public function setTienda(\App\CoreBundle\Entity\Tienda $tienda = null)
    {
        $this->tienda = $tienda;

        return $this;
    }

    /**
     * Get tienda
     *
     * @return \App\CoreBundle\Entity\Tienda
     */
    public function getTienda()
    {
        return $this->tienda;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->producto = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add producto
     *
     * @param \App\CoreBundle\Entity\Producto $producto
     *
     * @return Categoria
     */
    public function addProducto(\App\CoreBundle\Entity\Producto $producto)
    {
        $this->producto[] = $producto;

        return $this;
    }

    /**
     * Remove producto
     *
     * @param \App\CoreBundle\Entity\Producto $producto
     */
    public function removeProducto(\App\CoreBundle\Entity\Producto $producto)
    {
        $this->producto->removeElement($producto);
    }

    /**
     * Get producto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducto()
    {
        return $this->producto;
    }
}
