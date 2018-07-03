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
 * @ORM\Table(name="ec_atributos")
 */
class Atributo
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
     * @ORM\Column(type="string", length=191, unique=false, nullable=false)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Producto", inversedBy="articulo", cascade={"persist"})
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $producto;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\ValorAtributo", mappedBy="atributo", cascade={"persist"}, orphanRemoval=true)
     */
    protected $valor;

    /**
     * @Gedmo\Slug(fields={"id","name"},updatable=true)
     * @ORM\Column(unique=true)
     */
    private $slug;

    /**
     * Atributos constructor.
     */
    public function __construct()
    {
        $this->valor = new ArrayCollection();
    }

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
     * @return Atributo
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
     * Add valor
     *
     * @param \App\CoreBundle\Entity\ValorAtributo $valor
     *
     * @return Atributo
     */
    public function addValor(\App\CoreBundle\Entity\ValorAtributo $valor)
    {
        $this->valor[] = $valor;

        return $this;
    }

    /**
     * Remove valor
     *
     * @param \App\CoreBundle\Entity\ValorAtributo $valor
     */
    public function removeValor(\App\CoreBundle\Entity\ValorAtributo $valor)
    {
        $this->valor->removeElement($valor);
    }

    /**
     * Get valor
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set producto
     *
     * @param \App\CoreBundle\Entity\Producto $producto
     *
     * @return Atributo
     */
    public function setProducto(\App\CoreBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \App\CoreBundle\Entity\Producto
     */
    public function getProducto()
    {
        return $this->producto;
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
     * @return Atributo
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }


}
