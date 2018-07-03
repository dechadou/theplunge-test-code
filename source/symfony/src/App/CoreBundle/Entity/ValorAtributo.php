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
 * @ORM\Table(name="ec_valores_atributos")
 */
class ValorAtributo
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
     * @ORM\Column(type="text", unique=false, nullable=false)
     */
    protected $valor;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Atributo", inversedBy="valor", cascade={"persist"})
     * @ORM\JoinColumn(name="atributo_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $atributo;

    /**
     * @ORM\ManyToMany(targetEntity="App\CoreBundle\Entity\Articulo", mappedBy="valor_atributo")
     */
    protected $articulo;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ValorAtributo
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     * @return ValorAtributo
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAtributo()
    {
        return $this->atributo;
    }

    /**
     * @param mixed $atributo
     * @return ValorAtributo
     */
    public function setAtributo($atributo)
    {
        $this->atributo = $atributo;
        return $this;
    }



    public function __toString()
    {
        return ($this->getValor() != '') ? $this->getValor() : '';
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return ValorAtributo
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articulo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add articulo
     *
     * @param \App\CoreBundle\Entity\Articulo $articulo
     *
     * @return ValorAtributo
     */
    public function addArticulo(\App\CoreBundle\Entity\Articulo $articulo)
    {
        $this->articulo[] = $articulo;

        return $this;
    }

    /**
     * Remove articulo
     *
     * @param \App\CoreBundle\Entity\Articulo $articulo
     */
    public function removeArticulo(\App\CoreBundle\Entity\Articulo $articulo)
    {
        $this->articulo->removeElement($articulo);
    }

    /**
     * Get articulo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticulo()
    {
        return $this->articulo;
    }
}
