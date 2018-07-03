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
 * @ORM\Table(name="ec_email_triggers")
 */
class EmailTriggers
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
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Orders\Estado", inversedBy="email_trigger", cascade={"persist"})
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $estado;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Orders\Envio", inversedBy="email_trigger", cascade={"persist"})
     * @ORM\JoinColumn(name="envio_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $envio;

    /**
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    protected $subject;

    /**
     * @ORM\Column(type="text", unique=false, nullable=false)
     */
    protected $template;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $delay;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Tienda", inversedBy="email_trigger", cascade={"persist"})
     * @ORM\JoinColumn(name="tienda_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $tienda;




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
     * Set estado
     *
     * @param \App\CoreBundle\Entity\Orders\Estado $estado
     *
     * @return EmailTriggers
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
     * Set envio
     *
     * @param \App\CoreBundle\Entity\Orders\Envio $envio
     *
     * @return EmailTriggers
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
     * Set subject
     *
     * @param string $subject
     *
     * @return EmailTriggers
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set template
     *
     * @param string $template
     *
     * @return EmailTriggers
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function __toString()
    {
        return (string) 'Email Trigger #'.$this->getId();
    }

    /**
     * Set delay
     *
     * @param integer $delay
     *
     * @return EmailTriggers
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get delay
     *
     * @return integer
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set tienda
     *
     * @param \App\CoreBundle\Entity\Tienda $tienda
     *
     * @return EmailTriggers
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
}
