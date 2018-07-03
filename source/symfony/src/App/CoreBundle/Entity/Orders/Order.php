<?php

namespace App\CoreBundle\Entity\Orders;

use App\CoreBundle\Entity\Common\CreatedByEntity;
use App\CoreBundle\Entity\Common\PublishableEntity;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use MediaMonks\Doctrine\Mapping\Annotation as MediaMonks;

/**
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\OrderRepository")
 * @ORM\Table(name="ec_compras_cabecera")
 */
class Order
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
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    protected $woocomerce_order_id;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $tracking_code;

    /**
     * @ORM\Column(type="decimal", scale=2, unique=false, nullable=false)
     */
    protected $importe;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mercado_pago_id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $external_reference;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mercado_pago_preference_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Orders\Estado", inversedBy="order", cascade={"persist"})
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $estado;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Orders\Envio", inversedBy="order", cascade={"persist"})
     * @ORM\JoinColumn(name="envio_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $envio;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\Nota", mappedBy="order", cascade={"persist"}, orphanRemoval=true)
     */
    protected $nota;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Tienda", inversedBy="order", cascade={"persist"})
     * @ORM\JoinColumn(name="tienda_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $tienda;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\CompraCombo", mappedBy="order", cascade={"persist"}, orphanRemoval=true)
     */
    protected $compra_combo;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\CompraArticulo", mappedBy="order", cascade={"persist"}, orphanRemoval=true)
     */
    protected $compra_articulo;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\OrderUserData", mappedBy="order", cascade={"persist"}, orphanRemoval=true)
     */
    protected $user_data;


    public function __construct()
    {
        $this->nota = new ArrayCollection();
        $this->compra_combo = new ArrayCollection();
        $this->compra_articulo = new ArrayCollection();
        $this->user_data = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getIdNameEmail()
    {
        return '#' . $this->getWoocomerceOrderId() . ' Por ' . $this->getUserData()->first()->getFullName() . ' <br>' . $this->getUserData()->first()->getEmail();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Order
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        $gmtTimezone = new DateTimeZone('America/Argentina/Buenos_Aires');
        $dateTime = new DateTime($this->date->format('Y-m-d H:i:s'), $gmtTimezone);
        return $dateTime;
    }

    /**
     * Set importe
     *
     * @param integer $importe
     *
     * @return Order
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return integer
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set envio
     *
     * @param \App\CoreBundle\Entity\Orders\Envio $envio
     *
     * @return Order
     */
    public function setEnvio(\App\CoreBundle\Entity\Orders\Envio $envio = null)
    {
        $this->envio = $envio;

        return $this;
    }

    /**
     * Get envio
     *
     * @return \App\CoreBundle\Entity\Orders\Envio
     */
    public function getEnvio()
    {
        return $this->envio;
    }

    /**
     * Add notum
     *
     * @param \App\CoreBundle\Entity\Orders\Nota $notum
     *
     * @return Order
     */
    public function addNotum(\App\CoreBundle\Entity\Orders\Nota $notum)
    {
        $this->nota[] = $notum;

        return $this;
    }

    /**
     * Remove notum
     *
     * @param \App\CoreBundle\Entity\Orders\Nota $notum
     */
    public function removeNotum(\App\CoreBundle\Entity\Orders\Nota $notum)
    {
        $this->nota->removeElement($notum);
    }

    /**
     * Get nota
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set tienda
     *
     * @param \App\CoreBundle\Entity\Tienda $tienda
     *
     * @return Order
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
     * Add compraArticulo
     *
     * @param \App\CoreBundle\Entity\Orders\CompraArticulo $compraArticulo
     *
     * @return Order
     */
    public function addCompraArticulo(\App\CoreBundle\Entity\Orders\CompraArticulo $compraArticulo)
    {
        $this->compra_articulo[] = $compraArticulo;

        return $this;
    }

    /**
     * Remove compraArticulo
     *
     * @param \App\CoreBundle\Entity\Orders\CompraArticulo $compraArticulo
     */
    public function removeCompraArticulo(\App\CoreBundle\Entity\Orders\CompraArticulo $compraArticulo)
    {
        $this->compra_articulo->removeElement($compraArticulo);
    }

    /**
     * Get compraArticulo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompraArticulo()
    {
        return $this->compra_articulo;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * Add compraCombo
     *
     * @param \App\CoreBundle\Entity\Orders\CompraCombo $compraCombo
     *
     * @return Order
     */
    public function addCompraCombo(\App\CoreBundle\Entity\Orders\CompraCombo $compraCombo)
    {
        $this->compra_combo[] = $compraCombo;

        return $this;
    }

    /**
     * Remove compraCombo
     *
     * @param \App\CoreBundle\Entity\Orders\CompraCombo $compraCombo
     */
    public function removeCompraCombo(\App\CoreBundle\Entity\Orders\CompraCombo $compraCombo)
    {
        $this->compra_combo->removeElement($compraCombo);
    }

    /**
     * Get compraCombo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompraCombo()
    {
        return $this->compra_combo;
    }

    /**
     * @return mixed
     */
    public function getTrackingCode()
    {
        return $this->tracking_code;
    }

    /**
     * @param mixed $tracking_code
     * @return Order
     */
    public function setTrackingCode($tracking_code)
    {
        $this->tracking_code = $tracking_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getTotal()
    {
        return '$' . $this->getImporte();
    }

    /**
     * Set mercadoPagoId
     *
     * @param string $mercadoPagoId
     *
     * @return Order
     */
    public function setMercadoPagoId($mercadoPagoId)
    {
        $this->mercado_pago_id = $mercadoPagoId;

        return $this;
    }

    /**
     * Get mercadoPagoId
     *
     * @return string
     */
    public function getMercadoPagoId()
    {
        return $this->mercado_pago_id;
    }


    /**
     * Add userDatum
     *
     * @param \App\CoreBundle\Entity\Orders\OrderUserData $userDatum
     *
     * @return Order
     */
    public function addUserDatum(\App\CoreBundle\Entity\Orders\OrderUserData $userDatum)
    {
        $this->user_data[] = $userDatum;

        return $this;
    }

    /**
     * Remove userDatum
     *
     * @param \App\CoreBundle\Entity\Orders\OrderUserData $userDatum
     */
    public function removeUserDatum(\App\CoreBundle\Entity\Orders\OrderUserData $userDatum)
    {
        $this->user_data->removeElement($userDatum);
    }

    /**
     * Get userData
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserData()
    {
        return $this->user_data;
    }

    /**
     * @return mixed
     */
    public function getWoocomerceOrderId()
    {
        return $this->woocomerce_order_id;
    }

    /**
     * @param mixed $woocomerce_order_id
     * @return Order
     */
    public function setWoocomerceOrderId($woocomerce_order_id)
    {
        $this->woocomerce_order_id = $woocomerce_order_id;
        return $this;
    }


    /**
     * Set estado
     *
     * @param \App\CoreBundle\Entity\Orders\Estado $estado
     *
     * @return Order
     */
    public function setEstado(\App\CoreBundle\Entity\Orders\Estado $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \App\CoreBundle\Entity\Orders\Estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @return mixed
     */
    public function getMercadoPagoPreferenceId()
    {
        return $this->mercado_pago_preference_id;
    }

    /**
     * @param mixed $mercado_pago_preference_id
     * @return Order
     */
    public function setMercadoPagoPreferenceId($mercado_pago_preference_id)
    {
        $this->mercado_pago_preference_id = $mercado_pago_preference_id;
        return $this;
    }


    public function getUserBillingAddress()
    {
        return $this->getUserData()->first()->getBillingAddress();
    }

    public function getUserEmail()
    {
        return $this->getUserData()->first()->getEmail();
    }

    public function getUserFullName()
    {
        return $this->getUserData()->first()->getFullName();
    }

    public function getUserPhoneNumber()
    {
        return $this->getUserData()->first()->getPhoneNumber();
    }

    public function getUserBillingCountry()
    {
        return $this->getUserData()->first()->getBillingCountry();
    }

    public function getUserBillingPostCode()
    {
        return $this->getUserData()->first()->getBillingPostcode();
    }

    public function getUserBillingState()
    {
        return $this->getUserData()->first()->getBillingState();
    }

    public function getUserBillingCity()
    {
        return $this->getUserData()->first()->getBillingCity();
    }

    public function getUserBillingStreetNumber()
    {
        return $this->getUserData()->first()->getBillingAddressNumber();
    }

    public function getUserBillingFloor()
    {
        return $this->getUserData()->first()->getFloor();
    }

    public function getUserBillingApartment()
    {
        return $this->getUserData()->first()->getApartment();
    }

    public function getUserSendAddress()
    {
        return $this->getUserData()->first()->getSendAddress();
    }

    public function getTiendaAvatar()
    {
        return $this->getTienda()->getImage();
    }

    public function getUserAddressOrderList()
    {
        if (trim($this->getUserBillingAddress()) != '') {
            return
                $this->getUserBillingAddress() . ' ' . $this->getUserBillingStreetNumber() . ' ' . $this->getUserBillingFloor() . ' ' . $this->getUserBillingApartment()
                . ', ' .
                $this->getUserBillingCity() . ', ' . $this->getUserBillingState() . ', ' . $this->getUserBillingCountry()
                . ', CP: ' . $this->getUserBillingPostCode();
        }
        return '';
    }

    public function getActualizacionNota()
    {
        return $this->getNota()->first()->getUpdatedAt()->format('Y-m-d H:i:s');
    }

    public function getFullPrice()
    {

        return '$' . ($this->getImporte() + $this->getEnvio()->getPrice());
    }

    /**
     * @return mixed
     */
    public function getExternalReference()
    {
        return $this->external_reference;
    }

    /**
     * @param mixed $external_reference
     * @return Order
     */
    public function setExternalReference($external_reference)
    {
        $this->external_reference = $external_reference;
        return $this;
    }


    /*
     * Export fields
     */
    public function exportOrderId()
    {
        return '#' . $this->getWoocomerceOrderId();
    }

    public function exportTienda()
    {
        return $this->getTienda()->getName();
    }


    public function exportName()
    {
        return $this->getUserData()->first()->getFullName();
    }

    public function exportEmail()
    {
        return $this->getUserData()->first()->getEmail();
    }

    public function exportAddress()
    {
        return $this->getUserAddressOrderList();
    }

    public function exportCiudad()
    {
        return $this->getUserBillingCity();
    }

    public function exportPais()
    {
        return $this->getUserBillingCountry();
    }

    public function exportProvincia()
    {
        return $this->getUserBillingState();
    }

    public function exportArticulos()
    {
        $result = [];
        foreach ($this->getCompraArticulo() as $articulo) {
            $result[] = (string)$articulo->getCantidad() . ' x ' . $articulo->getArticulo()->getName();
        }
        if (!empty($result)) {
            return implode(',', $result);
        }
        return '';
    }

    public function exportCombos()
    {
        $result = [];
        foreach ($this->getCompraCombo() as $combo) {
            $result[] = (string)$combo->getCantidad() . ' x ' . $combo->getCombo()->getName();
        }
        if (!empty($result)) {
            return implode(',', $result);
        }
        return '';
    }

    public function exportTotal()
    {
        return $this->getFullPrice();
    }

    public function exportEnvio()
    {
        return $this->getEnvio()->getName();
    }

    public function exportTrackingCode()
    {
        return $this->getTrackingCode();
    }

    public function exportFechaCompra()
    {
        return $this->getDate();
    }

    public function exportEstado()
    {
        return $this->getEstado()->getName();
    }

    public function exportMercadoPagoId()
    {
        return $this->getMercadoPagoId();
    }


}
