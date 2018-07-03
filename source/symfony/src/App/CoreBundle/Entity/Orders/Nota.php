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
 * @ORM\Table(name="ec_compras_cab_notas")
 */
class Nota
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
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $nota;

    /**
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    protected $mail_usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Orders\Estado", inversedBy="nota", cascade={"persist"})
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $estado;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Orders\Order", inversedBy="nota", cascade={"persist"})
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $order;

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
     * Set nota
     *
     * @param string $nota
     *
     * @return Nota
     */
    public function setNota($nota)
    {
        $this->nota = $nota;

        return $this;
    }

    /**
     * Get nota
     *
     * @return string
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set mailUsuario
     *
     * @param string $mailUsuario
     *
     * @return Nota
     */
    public function setMailUsuario($mailUsuario)
    {
        $this->mail_usuario = $mailUsuario;

        return $this;
    }

    /**
     * Get mailUsuario
     *
     * @return string
     */
    public function getMailUsuario()
    {
        return $this->mail_usuario;
    }

    /**
     * Set estado
     *
     * @param \App\CoreBundle\Entity\Orders\Estado $estado
     *
     * @return Nota
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
     * Set order
     *
     * @param \App\CoreBundle\Entity\Orders\Order $order
     *
     * @return Nota
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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getNota();
    }

    public function getCreatedDate()
    {
        return $this->getCreatedAt()->format('Y-m-d H:i:s');
    }
}
