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
 * @ORM\Table(name="ec_compras_usuario")
 */
class OrderUserData
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Orders\Order", inversedBy="user_data")
     */
    protected $order;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $apartment;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $floor;

    /**
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $billing_address;

    /**
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $billing_city;

    /**
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $billing_address_number;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $billing_country;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $billing_postcode;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $billing_state;

    /**
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $send_address;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    protected $full_name;

    /**
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    protected $phone_number;

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
     * Set billingAddress
     *
     * @param string $billingAddress
     *
     * @return OrderUserData
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billing_address = $billingAddress;

        return $this;
    }

    /**
     * Get billingAddress
     *
     * @return string
     */
    public function getBillingAddress()
    {
        return $this->billing_address;
    }

    /**
     * Set billingCountry
     *
     * @param string $billingCountry
     *
     * @return OrderUserData
     */
    public function setBillingCountry($billingCountry)
    {
        $this->billing_country = $billingCountry;

        return $this;
    }

    /**
     * Get billingCountry
     *
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->billing_country;
    }

    /**
     * Set billingPostcode
     *
     * @param string $billingPostcode
     *
     * @return OrderUserData
     */
    public function setBillingPostcode($billingPostcode)
    {
        $this->billing_postcode = $billingPostcode;

        return $this;
    }

    /**
     * Get billingPostcode
     *
     * @return string
     */
    public function getBillingPostcode()
    {
        return $this->billing_postcode;
    }

    /**
     * Set billingState
     *
     * @param string $billingState
     *
     * @return OrderUserData
     */
    public function setBillingState($billingState)
    {
        $this->billing_state = $billingState;

        return $this;
    }

    /**
     * Get billingState
     *
     * @return string
     */
    public function getBillingState()
    {
        return $this->billing_state;
    }

    /**
     * Set sendAddress
     *
     * @param string $sendAddress
     *
     * @return OrderUserData
     */
    public function setSendAddress($sendAddress)
    {
        $this->send_address = $sendAddress;

        return $this;
    }

    /**
     * Get sendAddress
     *
     * @return string
     */
    public function getSendAddress()
    {
        return $this->send_address;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return OrderUserData
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return OrderUserData
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return OrderUserData
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Set order
     *
     * @param \App\CoreBundle\Entity\Orders\Order $order
     *
     * @return OrderUserData
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

    /**
     * Set apartment
     *
     * @param string $apartment
     *
     * @return OrderUserData
     */
    public function setApartment($apartment)
    {
        $this->apartment = $apartment;

        return $this;
    }

    /**
     * Get apartment
     *
     * @return string
     */
    public function getApartment()
    {
        return $this->apartment;
    }

    /**
     * Set floor
     *
     * @param string $floor
     *
     * @return OrderUserData
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Get floor
     *
     * @return string
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set billingAddressNumber
     *
     * @param string $billingAddressNumber
     *
     * @return OrderUserData
     */
    public function setBillingAddressNumber($billingAddressNumber)
    {
        $this->billing_address_number = $billingAddressNumber;

        return $this;
    }

    /**
     * Get billingAddressNumber
     *
     * @return string
     */
    public function getBillingAddressNumber()
    {
        return $this->billing_address_number;
    }

    /**
     * Set billingCity
     *
     * @param string $billingCity
     *
     * @return OrderUserData
     */
    public function setBillingCity($billingCity)
    {
        $this->billing_city = $billingCity;

        return $this;
    }

    /**
     * Get billingCity
     *
     * @return string
     */
    public function getBillingCity()
    {
        return $this->billing_city;
    }
}
