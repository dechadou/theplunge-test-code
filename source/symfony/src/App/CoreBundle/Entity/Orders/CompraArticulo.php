<?php

namespace App\CoreBundle\Entity\Orders;

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
 * @ORM\Table(name="ec_compra_detalle_articulos")
 */
class CompraArticulo
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
     * @ORM\Column(type="integer")
     */
    protected $cantidad;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Articulo", inversedBy="compra_articulo")
     */
    protected $articulo;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Orders\Order", inversedBy="compra_articulo")
     */
    protected $order;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $importe;



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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return CompraArticulo
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set articulo
     *
     * @param \App\CoreBundle\Entity\Articulo $articulo
     *
     * @return CompraArticulo
     */
    public function setArticulo(\App\CoreBundle\Entity\Articulo $articulo = null)
    {
        $this->articulo = $articulo;

        return $this;
    }

    /**
     * Get articulo
     *
     * @return \App\CoreBundle\Entity\Articulo
     */
    public function getArticulo()
    {
        return $this->articulo;
    }

    /**
     * Set order
     *
     * @param \App\CoreBundle\Entity\Orders\Order $order
     *
     * @return CompraArticulo
     */
    public function setOrder(\App\CoreBundle\Entity\Orders\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \App\CoreBundle\Entity\Orders\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function getArticuloName()
    {
        return (string) $this->getCantidad().' x '.$this->getArticulo()->getName();
    }

    public function __toString()
    {
        return (string) $this->getArticulo()->getName();
    }

    /**
     * Set importe
     *
     * @param string $importe
     *
     * @return CompraArticulo
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return string
     */
    public function getImporte()
    {
        return $this->importe;
    }
}
