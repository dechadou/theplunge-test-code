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
 * @ORM\Table(name="ec_compra_detalle_combo")
 */
class CompraCombo
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
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Combo", inversedBy="compra_combo")
     */
    protected $combo;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Orders\Order", inversedBy="compra_combo")
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
     * @return CompraCombo
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
     * Set combo
     *
     * @param \App\CoreBundle\Entity\Combo $combo
     *
     * @return CompraCombo
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
     * Set importe
     *
     * @param string $importe
     *
     * @return CompraCombo
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

    /**
     * Set order
     *
     * @param \App\CoreBundle\Entity\Orders\Order $order
     *
     * @return CompraCombo
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

    public function getComboName()
    {
        return (string) $this->getCantidad().' x '.$this->getCombo()->getName();
    }
}
