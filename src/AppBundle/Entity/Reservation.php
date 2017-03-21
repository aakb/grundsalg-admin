<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="Reservation", indexes={@ORM\Index(name="fk_InteressentGrundMapping_interessentId", columns={"interessentId"}), @ORM\Index(name="fk_InteressentGrundMapping_grundId", columns={"grundId"})})
 * @ORM\Entity
 */
class Reservation
{
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="annulleret", type="boolean", nullable=false)
   */
  private $annulleret;

  /**
   * @var \Grund
   *
   * @ORM\ManyToOne(targetEntity="Grund", inversedBy="reservationer")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="grundId", referencedColumnName="id")
   * })
   */
  private $grund;

  /**
   * @var \Interessent
   *
   * @ORM\ManyToOne(targetEntity="Interessent", inversedBy="reservationer")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="interessentId", referencedColumnName="id")
   * })
   */
  private $interessent;


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
   * Set annulleret
   *
   * @param string $annulleret
   *
   * @return Reservation
   */
  public function setAnnulleret($annulleret)
  {
    $this->annulleret = $annulleret;

    return $this;
  }

  /**
   * Get annulleret
   *
   * @return string
   */
  public function getAnnulleret()
  {
    return $this->annulleret;
  }

  /**
   * Set grundid
   *
   * @param \AppBundle\Entity\Grund $grund
   *
   * @return Reservation
   */
  public function setGrund(\AppBundle\Entity\Grund $grund = null)
  {
    $this->grund = $grund;

    return $this;
  }

  /**
   * Get grundid
   *
   * @return \AppBundle\Entity\Grund
   */
  public function getGrund()
  {
    return $this->grund;
  }

  /**
   * Set interessentid
   *
   * @param \AppBundle\Entity\Interessent $interessent
   *
   * @return Reservation
   */
  public function setInteressent(\AppBundle\Entity\Interessent $interessent = null)
  {
    $this->interessent = $interessent;

    return $this;
  }

  /**
   * Get interessentid
   *
   * @return \AppBundle\Entity\Interessent
   */
  public function getInteressent()
  {
    return $this->interessent;
  }

  public function __toString()
  {
    return __CLASS__;
  }
}