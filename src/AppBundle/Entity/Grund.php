<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\GrundPublicStatus;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Psr\Log\NullLogger;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Grund
 *
 * @ORM\Table(name="Grund", indexes={
 *   @ORM\Index(name="fk_Grund_lokalsamfundId", columns={"lokalsamfundId"}),
 *   @ORM\Index(name="fk_Grund_salgsomraadeId", columns={"salgsomraadeId"}),
 *   @ORM\Index(name="fk_Grund_koeberPostById", columns={"koeberPostById"}),
 *   @ORM\Index(name="fk_Grund_medKoeberPostById", columns={"medKoeberPostById"}),
 *
 *   @ORM\Index(name="search_Grund_vej", columns={"vej"}),
 *   @ORM\Index(name="search_Grund_husnummer", columns={"husnummer"}),
 *   @ORM\Index(name="search_Grund_bogstav", columns={"bogstav"}),
 *   @ORM\Index(name="search_Grund_salgsType", columns={"salgsType"}),
 *   @ORM\Index(name="search_Grund_areal", columns={"areal"}),
 *   @ORM\Index(name="search_Grund_pris", columns={"pris"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GrundRepository")
 *
 * @InheritanceType("SINGLE_TABLE")
 *
 * @DiscriminatorColumn(name="discr", type="string")
 *
 * @DiscriminatorMap({"GRUND" = "Grund", "COLL" = "GrundCollection"})
 *
 * @Gedmo\Loggable
 */
class Grund {
  use BlameableEntity;
  use TimestampableEntity;

  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var GrundStatus
   *
   * @ORM\Column(name="status", type="GrundStatus", nullable=true)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\GrundStatus")
   *
   * @Gedmo\Versioned
   */
  private $status;

  /**
   * @var GrundSalgStatus
   *
   * @ORM\Column(name="salgStatus", type="GrundSalgStatus", nullable=true)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\GrundSalgStatus")
   *
   * @Gedmo\Versioned
   */
  private $salgstatus;

  /**
   * @var GrundPublicStatus
   *
   * @ORM\Column(name="publicStatus", type="GrundPublicStatus", nullable=true)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\GrundPublicStatus")
   *
   * @Gedmo\Versioned
   */
  private $publicstatus;

  /**
   * @var string
   *
   * @ORM\Column(name="gid", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $gid;

  /**
   * @var GrundType
   *
   * @ORM\Column(name="type", type="GrundType", nullable=false)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\GrundType")
   *
   * @Gedmo\Versioned
   */
  private $type;

  /**
   * @var string
   *
   * @ORM\Column(name="mnr", type="string", length=20, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $mnr;

  /**
   * @var string
   *
   * @ORM\Column(name="mnr2", type="string", length=20, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $mnr2;

  /**
   * @var string
   *
   * @ORM\Column(name="delAreal", type="string", length=60, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $delareal;

  /**
   * @var string
   *
   * @ORM\Column(name="ejerlav", type="string", length=60, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $ejerlav;

  /**
   * @var string
   *
   * @ORM\Column(name="vej", type="string", length=60, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $vej;

  /**
   * @var int
   *
   * @ORM\Column(name="husNummer", type="integer", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $husnummer;

  /**
   * @var string
   *
   * @ORM\Column(name="bogstav", type="string", length=30, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $bogstav;

  /**
   * @var \Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby", fetch="EAGER")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="postbyId", referencedColumnName="id")
   * })
   * @ORM\OrderBy({"postalcode" = "ASC"})
   *
   * @Gedmo\Versioned
   */
  protected $postby;

  /**
   * @var SalgsType
   *
   * @ORM\Column(name="salgsType", type="SalgsType", nullable=false)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\SalgsType")
   *
   * @Gedmo\Versioned
   */
  private $salgstype;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="auktionStartDato", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $auktionstartdato;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="auktionSlutDato", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $auktionslutdato;

  /**
   * @var bool
   *
   * @ORM\Column(name="annonceres", type="boolean", nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $annonceres;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="datoAnnonce", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $datoannonce;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="datoAnnonce1", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $datoannonce1;

  /**
   * @var array
   *
   * @ORM\Column(name="tilsluttet", type="array", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $tilsluttet;

  /**
   * @var decimal
   *
   * @ORM\Column(name="maxEtageM2", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $maxetagem2;

  /**
   * @var decimal
   *
   * @ORM\Column(name="areal", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $areal;

  /**
   * @var decimal
   *
   * @ORM\Column(name="arealVej", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $arealvej;

  /**
   * @var decimal
   *
   * @ORM\Column(name="arealKotelet", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $arealkotelet;

  /**
   * @var decimal
   *
   * @ORM\Column(name="bruttoAreal", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $bruttoareal;

  /**
   * @var decimal
   *
   * @ORM\Column(name="prisM2", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $prism2;

  /**
   * @var decimal
   *
   * @ORM\Column(name="prisFoerKorrektion", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $prisfoerkorrektion;

  /**
   * @var string
   *
   * @ORM\Column(name="prisKorrektion1", type="Priskorrektion", nullable=true)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Priskorrektion")
   *
   * @Gedmo\Versioned
   */
  private $priskorrektion1;

  /**
   * @var int
   *
   * @ORM\Column(name="antalKorr1", type="integer", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $antalkorr1;

  /**
   * @var decimal
   *
   * @ORM\Column(name="aKrKorr1", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $akrkorr1;

  /**
   * @var decimal
   *
   * @ORM\Column(name="prisKoor1", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $priskoor1;

  /**
   * @var string
   *
   * @ORM\Column(name="prisKorrektion2", type="Priskorrektion", nullable=true)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Priskorrektion")
   *
   * @Gedmo\Versioned
   */
  private $priskorrektion2;

  /**
   * @var int
   *
   * @ORM\Column(name="antalKorr2", type="integer", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $antalkorr2;

  /**
   * @var decimal
   *
   * @ORM\Column(name="aKrKorr2", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $akrkorr2;

  /**
   * @var decimal
   *
   * @ORM\Column(name="prisKoor2", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $priskoor2;

  /**
   * @var string
   *
   * @ORM\Column(name="prisKorrektion3", type="Priskorrektion", nullable=true)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Priskorrektion")
   *
   * @Gedmo\Versioned
   */
  private $priskorrektion3;

  /**
   * @var int
   *
   * @ORM\Column(name="antalKorr3", type="integer", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $antalkorr3;

  /**
   * @var decimal
   *
   * @ORM\Column(name="aKrKorr3", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $akrkorr3;

  /**
   * @var decimal
   *
   * @ORM\Column(name="prisKoor3", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $priskoor3;

  /**
   * @var decimal
   *
   * @ORM\Column(name="pris", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $pris;

  /**
   * @var decimal
   *
   * @ORM\Column(name="fastPris", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $fastpris;

  /**
   * @var decimal
   *
   * @ORM\Column(name="minBud", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $minbud;

  /**
   * @var string
   *
   * @ORM\Column(name="note", type="text", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $note;

  /**
   * @var \AppBundle\Entity\LandInspektoer
   *
   * @ORM\ManyToOne(targetEntity="Landinspektoer")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="landInspektoerId", referencedColumnName="id", nullable=true)
   * })
   *
   * @Gedmo\Versioned
   */
  private $landinspektoer;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="resStart", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $resstart;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="tilbudStart", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $tilbudstart;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="accept", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $accept;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="skoedeRekv", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $skoederekv;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="beloebAnvist", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $beloebanvist;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="resSlut", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $resslut;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="tilbudSlut", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $tilbudslut;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="overtagelse", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $overtagelse;

  /**
   * @var decimal
   *
   * @ORM\Column(name="antagetBud", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $antagetbud;

  /**
   * @var decimal
   *
   * @ORM\Column(name="salgsPrisUMoms", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $salgsprisumoms;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberNavn", type="string", length=255, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberNavn;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberAdresse", type="string", length=120, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberAdresse;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberLand", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberLand;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberTelefon", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberTelefon;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberMobil", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberMobil;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberEmail", type="string", length=120, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberEmail;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberNavn", type="string", length=255, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberNavn;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberAdresse", type="string", length=120, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberAdresse;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberLand", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberLand;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberTelefon", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberTelefon;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberMobil", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberMobil;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberEmail", type="string", length=120, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberEmail;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberNotat", type="text", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberNotat;

  /**
   * @var \AppBundle\Entity\Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="medKoeberPostById", referencedColumnName="id")
   * })
   * @ORM\OrderBy({"postalcode" = "ASC"})
   *
   * @Gedmo\Versioned
   */
  private $medkoeberPostby;

  /**
   * @var \AppBundle\Entity\Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="koeberPostById", referencedColumnName="id")
   * })
   * @ORM\OrderBy({"postalcode" = "ASC"})
   *
   * @Gedmo\Versioned
   */
  private $koeberPostby;

  /**
   * @var \AppBundle\Entity\Reservation
   *
   * @OneToMany(targetEntity="Reservation", mappedBy="grund", cascade={"persist", "remove"})
   */
  private $reservationer;

  /**
   * @var \AppBundle\Entity\Salgshistorik
   *
   * @OneToMany(targetEntity="Salgshistorik", mappedBy="grund", cascade={"persist", "remove"})
   */
  private $salgshistorik;

  /**
   * @var \AppBundle\Entity\Lokalsamfund
   *
   * @ORM\ManyToOne(targetEntity="Lokalsamfund")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="lokalsamfundId", referencedColumnName="id", nullable=false)
   * })
   *
   * @Gedmo\Versioned
   */
  private $lokalsamfund;

  /**
   * @var \AppBundle\Entity\Salgsomraade
   *
   * @ORM\ManyToOne(targetEntity="Salgsomraade", fetch="EAGER")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="salgsomraadeId", referencedColumnName="id", nullable=false)
   * })
   *
   * @Gedmo\Versioned
   */
  private $salgsomraade;

  /**
   * @var \CrEOF\Spatial\DBAL\Types\Geography
   *
   * @ORM\Column(name="SP_GEOMETRY", type="geometry", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $spGeometry;

  /**
   * @var int
   *
   * @ORM\Column(name="srid", type="integer", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $srid;

  /**
   * @var string
   *
   * @ORM\Column(name="MI_STYLE", type="string", length=255, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $miStyle;

  /**
   * @var string
   *
   * @ORM\Column(name="pdflink", type="string", length=255, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $pdfLink;

  /**
   * Grund constructor.
   */
  public function __construct() {
    $this->reservationer = new ArrayCollection();
    $this->salgshistorik = new ArrayCollection();
    $this->tilsluttet = [];
    $this->annonceres = FALSE;
    $this->setType(GrundType::PARCELHUS);
    $this->setStatus(GrundStatus::LEDIG);
    $this->setSalgstype(SalgsType::AUKTION);
  }

  /**
   * Mainly used in form generation.
   *
   * @return string
   */
  public function __toString() {
    return $this->getVej() . ' ' . $this->getHusnummer() . $this->getBogstav()
      . ($this->getPostby() ? ', ' . $this->getPostby() : '');
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set status
   *
   * @param GrundStatus $status
   *
   * @return Grund
   */
  public function setStatus($status) {
    $this->status = $status;

    return $this;
  }

  /**
   * Get status
   *
   * @return GrundStatus
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * @return GrundPublicStatus
   */
  public function getPublicstatus() {
    return $this->publicstatus;
  }

  /**
   * @param GrundPublicStatus $publicstatus
   */
  public function setPublicstatus($publicstatus) {
    $this->publicstatus = $publicstatus;
  }

  /**
   * Set salgstatus
   *
   * @param GrundSalgStatus $salgstatus
   *
   * @return Grund
   */
  public function setSalgstatus($salgstatus) {
    $this->salgstatus = $salgstatus;

    return $this;
  }

  /**
   * Get salgstatus
   *
   * @return GrundSalgStatus
   */
  public function getSalgstatus() {
    return $this->salgstatus;
  }

  /**
   * Set gid
   *
   * @param string $gid
   *
   * @return Grund
   */
  public function setGid($gid) {
    $this->gid = $gid;

    return $this;
  }

  /**
   * Get gid
   *
   * @return string
   */
  public function getGid() {
    return $this->gid;
  }

  /**
   * Set grundtype
   *
   * @param GrundType $type
   *
   * @return Grund
   */
  public function setType($type) {
    $this->type = $type;

    return $this;
  }

  /**
   * Get grundtype
   *
   * @return GrundType
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Set mnr
   *
   * @param string $mnr
   *
   * @return Grund
   */
  public function setMnr($mnr) {
    $this->mnr = $mnr;

    return $this;
  }

  /**
   * Get mnr
   *
   * @return string
   */
  public function getMnr() {
    return $this->mnr;
  }

  /**
   * @return string
   */
  public function getMnrSamlet() {
    return $this->getMnr() . $this->getMnr2();
  }

  /**
   * Set mnr2
   *
   * @param string $mnr2
   *
   * @return Grund
   */
  public function setMnr2($mnr2) {
    $this->mnr2 = $mnr2;

    return $this;
  }

  /**
   * Get mnr2
   *
   * @return string
   */
  public function getMnr2() {
    return $this->mnr2;
  }

  /**
   * Set delareal
   *
   * @param string $delareal
   *
   * @return Grund
   */
  public function setDelareal($delareal) {
    $this->delareal = $delareal;

    return $this;
  }

  /**
   * Get delareal
   *
   * @return string
   */
  public function getDelareal() {
    return $this->delareal;
  }

  /**
   * Set ejerlav
   *
   * @param string $ejerlav
   *
   * @return Grund
   */
  public function setEjerlav($ejerlav) {
    $this->ejerlav = $ejerlav;

    return $this;
  }

  /**
   * Get ejerlav
   *
   * @return string
   */
  public function getEjerlav() {
    return $this->ejerlav;
  }

  /**
   * Set vej
   *
   * @param string $vej
   *
   * @return Grund
   */
  public function setVej($vej) {
    $this->vej = $vej;

    return $this;
  }

  /**
   * Get vej
   *
   * @return string
   */
  public function getVej() {
    return $this->vej;
  }

  /**
   * Set husnummer
   *
   * @param string $husnummer
   *
   * @return Grund
   */
  public function setHusnummer($husnummer) {
    $this->husnummer = $husnummer;

    return $this;
  }

  /**
   * Get husnummer
   *
   * @return string
   */
  public function getHusnummer() {
    return $this->husnummer;
  }

  /**
   * Set bogstav
   *
   * @param string $bogstav
   *
   * @return Grund
   */
  public function setBogstav($bogstav) {
    $this->bogstav = $bogstav;

    return $this;
  }

  /**
   * Get bogstav
   *
   * @return string
   */
  public function getBogstav() {
    return $this->bogstav;
  }

  /**
   * Set postbyid
   *
   * @param \AppBundle\Entity\Postby $postby
   *
   * @return Grund
   */
  public function setPostby($postby) {
    $this->postby = $postby;

    return $this;
  }

  /**
   * Get postbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getPostby() {
    return $this->postby;
  }

  /**
   * Set salgstype
   *
   * @param SalgsType $salgstype
   *
   * @return Grund
   */
  public function setSalgstype($salgstype) {
    $this->salgstype = $salgstype;

    return $this;
  }

  /**
   * Get salgstype
   *
   * @return SalgsType
   */
  public function getSalgstype() {
    return $this->salgstype;
  }

  /**
   * Set auktionstartdato
   *
   * @param \DateTime $auktionstartdato
   *
   * @return Grund
   */
  public function setAuktionstartdato($auktionstartdato) {
    $this->auktionstartdato = $auktionstartdato;

    return $this;
  }

  /**
   * Get auktionstartdato
   *
   * @return \DateTime
   */
  public function getAuktionstartdato() {
    return $this->auktionstartdato;
  }

  /**
   * Set auktionslutdato
   *
   * @param \DateTime $auktionslutdato
   *
   * @return Grund
   */
  public function setAuktionslutdato($auktionslutdato) {
    $this->auktionslutdato = $auktionslutdato;

    return $this;
  }

  /**
   * Get auktionslutdato
   *
   * @return \DateTime
   */
  public function getAuktionslutdato() {
    return $this->auktionslutdato;
  }

  /**
   * @return bool
   */
  public function isAnnonceres(): bool {
    return $this->annonceres;
  }

  /**
   * @param bool $annonceres
   */
  public function setAnnonceres(bool $annonceres) {
    $this->annonceres = $annonceres;
  }

  /**
   * Set datoannonce
   *
   * @param \DateTime $datoannonce
   *
   * @return Grund
   */
  public function setDatoannonce($datoannonce) {
    $this->datoannonce = $datoannonce;

    return $this;
  }

  /**
   * Get datoannonce
   *
   * @return \DateTime
   */
  public function getDatoannonce() {
    return $this->datoannonce;
  }

  /**
   * Set datoannonce1
   *
   * @param \DateTime $datoannonce1
   *
   * @return Grund
   */
  public function setDatoannonce1($datoannonce1) {
    $this->datoannonce1 = $datoannonce1;

    return $this;
  }

  /**
   * Get datoannonce1
   *
   * @return \DateTime
   */
  public function getDatoannonce1() {
    return $this->datoannonce1;
  }

  /**
   * @return array
   */
  public function getTilsluttet() {
    return $this->tilsluttet;
  }

  /**
   * @param array $tilsluttet
   */
  public function setTilsluttet(array $tilsluttet) {
    $this->tilsluttet = $tilsluttet;
  }

  /**
   * Set maxetagem2
   *
   * @param decimal $maxetagem2
   *
   * @return Grund
   */
  public function setMaxetagem2($maxetagem2) {
    $this->maxetagem2 = $maxetagem2;

    return $this;
  }

  /**
   * Get maxetagem2
   *
   * @return decimal
   */
  public function getMaxetagem2() {
    return $this->maxetagem2;
  }

  /**
   * Set areal
   *
   * @param decimal $areal
   *
   * @return Grund
   */
  public function setAreal($areal) {
    $this->areal = $areal;

    return $this;
  }

  /**
   * Get areal
   *
   * @return decimal
   */
  public function getAreal() {
    return $this->areal;
  }

  /**
   * Set arealvej
   *
   * @param decimal $arealvej
   *
   * @return Grund
   */
  public function setArealvej($arealvej) {
    $this->arealvej = $arealvej;

    return $this;
  }

  /**
   * Get arealvej
   *
   * @return decimal
   */
  public function getArealvej() {
    return $this->arealvej;
  }

  /**
   * Set arealkotelet
   *
   * @param decimal $arealkotelet
   *
   * @return Grund
   */
  public function setArealkotelet($arealkotelet) {
    $this->arealkotelet = $arealkotelet;

    return $this;
  }

  /**
   * Get arealkotelet
   *
   * @return decimal
   */
  public function getArealkotelet() {
    return $this->arealkotelet;
  }

  /**
   * Set bruttoareal
   *
   * @param decimal $bruttoareal
   *
   * @return Grund
   */
  public function setBruttoareal($bruttoareal) {
    $this->bruttoareal = $bruttoareal;

    return $this;
  }

  /**
   * Get bruttoareal
   *
   * @return decimal
   */
  public function getBruttoareal() {
    return $this->bruttoareal;
  }

  /**
   * Set prism2
   *
   * @param decimal $prism2
   *
   * @return Grund
   */
  public function setPrism2($prism2) {
    $this->prism2 = $prism2;

    return $this;
  }

  /**
   * Get prism2
   *
   * @return decimal
   */
  public function getPrism2() {
    return $this->prism2;
  }

  /**
   * Set prisfoerkorrektion
   *
   * @param decimal $prisfoerkorrektion
   *
   * @return Grund
   */
  public function setPrisfoerkorrektion($prisfoerkorrektion) {
    $this->prisfoerkorrektion = $prisfoerkorrektion;

    return $this;
  }

  /**
   * Get prisfoerkorrektion
   *
   * @return decimal
   */
  public function getPrisfoerkorrektion() {
    return $this->prisfoerkorrektion;
  }

  /**
   * Set priskorrektion1
   *
   * @param string $priskorrektion1
   *
   * @return Grund
   */
  public function setPriskorrektion1($priskorrektion1) {
    $this->priskorrektion1 = $priskorrektion1;

    return $this;
  }

  /**
   * Get priskorrektion1
   *
   * @return string
   */
  public function getPriskorrektion1() {
    return $this->priskorrektion1;
  }

  /**
   * Set antalkorr1
   *
   * @param integer $antalkorr1
   *
   * @return Grund
   */
  public function setAntalkorr1($antalkorr1) {
    $this->antalkorr1 = $antalkorr1;

    return $this;
  }

  /**
   * Get antalkorr1
   *
   * @return integer
   */
  public function getAntalkorr1() {
    return $this->antalkorr1;
  }

  /**
   * Set akrkorr1
   *
   * @param decimal $akrkorr1
   *
   * @return Grund
   */
  public function setAkrkorr1($akrkorr1) {
    $this->akrkorr1 = $akrkorr1;

    return $this;
  }

  /**
   * Get akrkorr1
   *
   * @return decimal
   */
  public function getAkrkorr1() {
    return $this->akrkorr1;
  }

  /**
   * Set priskoor1
   *
   * @param decimal $priskoor1
   *
   * @return Grund
   */
  public function setPriskoor1($priskoor1) {
    $this->priskoor1 = $priskoor1;

    return $this;
  }

  /**
   * Get priskoor1
   *
   * @return decimal
   */
  public function getPriskoor1() {
    return $this->priskoor1;
  }

  /**
   * Set priskorrektion2
   *
   * @param string $priskorrektion2
   *
   * @return Grund
   */
  public function setPriskorrektion2($priskorrektion2) {
    $this->priskorrektion2 = $priskorrektion2;

    return $this;
  }

  /**
   * Get priskorrektion2
   *
   * @return string
   */
  public function getPriskorrektion2() {
    return $this->priskorrektion2;
  }

  /**
   * Set antalkorr2
   *
   * @param integer $antalkorr2
   *
   * @return Grund
   */
  public function setAntalkorr2($antalkorr2) {
    $this->antalkorr2 = $antalkorr2;

    return $this;
  }

  /**
   * Get antalkorr2
   *
   * @return integer
   */
  public function getAntalkorr2() {
    return $this->antalkorr2;
  }

  /**
   * Set akrkorr2
   *
   * @param decimal $akrkorr2
   *
   * @return Grund
   */
  public function setAkrkorr2($akrkorr2) {
    $this->akrkorr2 = $akrkorr2;

    return $this;
  }

  /**
   * Get akrkorr2
   *
   * @return decimal
   */
  public function getAkrkorr2() {
    return $this->akrkorr2;
  }

  /**
   * Set priskoor2
   *
   * @param decimal $priskoor2
   *
   * @return Grund
   */
  public function setPriskoor2($priskoor2) {
    $this->priskoor2 = $priskoor2;

    return $this;
  }

  /**
   * Get priskoor2
   *
   * @return decimal
   */
  public function getPriskoor2() {
    return $this->priskoor2;
  }

  /**
   * Set priskorrektion3
   *
   * @param string $priskorrektion3
   *
   * @return Grund
   */
  public function setPriskorrektion3($priskorrektion3) {
    $this->priskorrektion3 = $priskorrektion3;

    return $this;
  }

  /**
   * Get priskorrektion3
   *
   * @return string
   */
  public function getPriskorrektion3() {
    return $this->priskorrektion3;
  }

  /**
   * Set antalkorr3
   *
   * @param integer $antalkorr3
   *
   * @return Grund
   */
  public function setAntalkorr3($antalkorr3) {
    $this->antalkorr3 = $antalkorr3;

    return $this;
  }

  /**
   * Get antalkorr3
   *
   * @return integer
   */
  public function getAntalkorr3() {
    return $this->antalkorr3;
  }

  /**
   * Set akrkorr3
   *
   * @param decimal $akrkorr3
   *
   * @return Grund
   */
  public function setAkrkorr3($akrkorr3) {
    $this->akrkorr3 = $akrkorr3;

    return $this;
  }

  /**
   * Get akrkorr3
   *
   * @return decimal
   */
  public function getAkrkorr3() {
    return $this->akrkorr3;
  }

  /**
   * Set priskoor3
   *
   * @param decimal $priskoor3
   *
   * @return Grund
   */
  public function setPriskoor3($priskoor3) {
    $this->priskoor3 = $priskoor3;

    return $this;
  }

  /**
   * Get priskoor3
   *
   * @return decimal
   */
  public function getPriskoor3() {
    return $this->priskoor3;
  }

  /**
   * Set pris
   *
   * @param decimal $pris
   *
   * @return Grund
   */
  public function setPris($pris) {
    $this->pris = $pris;

    return $this;
  }

  /**
   * Get pris
   *
   * @return decimal
   */
  public function getPris() {
    return $this->pris;
  }

  /**
   * Set fastpris
   *
   * @param decimal $fastpris
   *
   * @return Grund
   */
  public function setFastpris($fastpris) {
    $this->fastpris = $fastpris;

    return $this;
  }

  /**
   * Get fastpris
   *
   * @return decimal
   */
  public function getFastpris() {
    return $this->fastpris;
  }

  /**
   * Set minbud
   *
   * @param decimal $minbud
   *
   * @return Grund
   */
  public function setMinbud($minbud) {
    $this->minbud = $minbud;

    return $this;
  }

  /**
   * Get minbud
   *
   * @return decimal
   */
  public function getMinbud() {
    return $this->minbud;
  }

  /**
   * Set note
   *
   * @param string $note
   *
   * @return Grund
   */
  public function setNote($note) {
    $this->note = $note;

    return $this;
  }

  /**
   * Get note
   *
   * @return string
   */
  public function getNote() {
    return $this->note;
  }

  /**
   * Set Landinspektoer
   *
   * @param string $landinspektoer
   *
   * @return Grund
   */
  public function setLandinspektoer($landinspektoer) {
    $this->landinspektoer = $landinspektoer;

    return $this;
  }

  /**
   * Get landinspektoerid
   *
   * @return string
   */
  public function getLandinspektoer() {
    return $this->landinspektoer;
  }

  /**
   * Set resstart
   *
   * @param \DateTime $resstart
   *
   * @return Grund
   */
  public function setResstart($resstart) {
    $this->resstart = $resstart;

    return $this;
  }

  /**
   * Get resstart
   *
   * @return \DateTime
   */
  public function getResstart() {
    return $this->resstart;
  }

  /**
   * Set tilbudstart
   *
   * @param \DateTime $tilbudstart
   *
   * @return Grund
   */
  public function setTilbudstart($tilbudstart) {
    $this->tilbudstart = $tilbudstart;

    return $this;
  }

  /**
   * Get tilbudstart
   *
   * @return \DateTime
   */
  public function getTilbudstart() {
    return $this->tilbudstart;
  }

  /**
   * Set accept
   *
   * @param \DateTime $accept
   *
   * @return Grund
   */
  public function setAccept($accept) {
    $this->accept = $accept;

    return $this;
  }

  /**
   * Get accept
   *
   * @return \DateTime
   */
  public function getAccept() {
    return $this->accept;
  }

  /**
   * Set skoederekv
   *
   * @param \DateTime $skoederekv
   *
   * @return Grund
   */
  public function setSkoederekv($skoederekv) {
    $this->skoederekv = $skoederekv;

    return $this;
  }

  /**
   * Get skoederekv
   *
   * @return \DateTime
   */
  public function getSkoederekv() {
    return $this->skoederekv;
  }

  /**
   * Set beloebanvist
   *
   * @param \DateTime $beloebanvist
   *
   * @return Grund
   */
  public function setBeloebanvist($beloebanvist) {
    $this->beloebanvist = $beloebanvist;

    return $this;
  }

  /**
   * Get beloebanvist
   *
   * @return \DateTime
   */
  public function getBeloebanvist() {
    return $this->beloebanvist;
  }

  /**
   * Set resslut
   *
   * @param \DateTime $resslut
   *
   * @return Grund
   */
  public function setResslut($resslut) {
    $this->resslut = $resslut;

    return $this;
  }

  /**
   * Get resslut
   *
   * @return \DateTime
   */
  public function getResslut() {
    return $this->resslut;
  }

  /**
   * Set tilbudslut
   *
   * @param \DateTime $tilbudslut
   *
   * @return Grund
   */
  public function setTilbudslut($tilbudslut) {
    $this->tilbudslut = $tilbudslut;

    return $this;
  }

  /**
   * Get tilbudslut
   *
   * @return \DateTime
   */
  public function getTilbudslut() {
    return $this->tilbudslut;
  }

  /**
   * Set overtagelse
   *
   * @param \DateTime $overtagelse
   *
   * @return Grund
   */
  public function setOvertagelse($overtagelse) {
    $this->overtagelse = $overtagelse;

    return $this;
  }

  /**
   * Get overtagelse
   *
   * @return \DateTime
   */
  public function getOvertagelse() {
    return $this->overtagelse;
  }

  /**
   * Set antagetbud
   *
   * @param decimal $antagetbud
   *
   * @return Grund
   */
  public function setAntagetbud($antagetbud) {
    $this->antagetbud = $antagetbud;

    return $this;
  }

  /**
   * Get antagetbud
   *
   * @return decimal
   */
  public function getAntagetbud() {
    return $this->antagetbud;
  }

  /**
   * Set salgsprisumoms
   *
   * @param decimal $salgsprisumoms
   *
   * @return Grund
   */
  public function setSalgsprisumoms($salgsprisumoms) {
    $this->salgsprisumoms = $salgsprisumoms;

    return $this;
  }

  /**
   * Get salgsprisumoms
   *
   * @return decimal
   */
  public function getSalgsprisumoms() {
    return $this->salgsprisumoms;
  }

  /**
   * Set navn
   *
   * @param string $koeberNavn
   *
   * @return Grund
   */
  public function setKoeberNavn($koeberNavn) {
    $this->koeberNavn = $koeberNavn;

    return $this;
  }

  /**
   * Get navn
   *
   * @return string
   */
  public function getKoeberNavn() {
    return $this->koeberNavn;
  }

  /**
   * Set adresse
   *
   * @param string $koeberAdresse
   *
   * @return Grund
   */
  public function setKoeberAdresse($koeberAdresse) {
    $this->koeberAdresse = $koeberAdresse;

    return $this;
  }

  /**
   * Get adresse
   *
   * @return string
   */
  public function getKoeberAdresse() {
    return $this->koeberAdresse;
  }

  /**
   * Set land
   *
   * @param string $koeberLand
   *
   * @return Grund
   */
  public function setKoeberLand($koeberLand) {
    $this->koeberLand = $koeberLand;

    return $this;
  }

  /**
   * Get land
   *
   * @return string
   */
  public function getKoeberLand() {
    return $this->koeberLand;
  }

  /**
   * Set telefon
   *
   * @param string $koeberTelefon
   *
   * @return Grund
   */
  public function setKoeberTelefon($koeberTelefon) {
    $this->koeberTelefon = $koeberTelefon;

    return $this;
  }

  /**
   * Get telefon
   *
   * @return string
   */
  public function getKoeberTelefon() {
    return $this->koeberTelefon;
  }

  /**
   * Set mobil
   *
   * @param string $koeberMobil
   *
   * @return Grund
   */
  public function setKoeberMobil($koeberMobil) {
    $this->koeberMobil = $koeberMobil;

    return $this;
  }

  /**
   * Get mobil
   *
   * @return string
   */
  public function getKoeberMobil() {
    return $this->koeberMobil;
  }

  /**
   * Set koeberemail
   *
   * @param string $koeberEmail
   *
   * @return Grund
   */
  public function setKoeberEmail($koeberEmail) {
    $this->koeberEmail = $koeberEmail;

    return $this;
  }

  /**
   * Get koeberemail
   *
   * @return string
   */
  public function getKoeberEmail() {
    return $this->koeberEmail;
  }

  /**
   * Set navn1
   *
   * @param string $medkoeberNavn
   *
   * @return Grund
   */
  public function setMedkoeberNavn($medkoeberNavn) {
    $this->medkoeberNavn = $medkoeberNavn;

    return $this;
  }

  /**
   * Get navn1
   *
   * @return string
   */
  public function getMedkoeberNavn() {
    return $this->medkoeberNavn;
  }

  /**
   * Set adresse1
   *
   * @param string $medkoeberAdresse
   *
   * @return Grund
   */
  public function setMedkoeberAdresse($medkoeberAdresse) {
    $this->medkoeberAdresse = $medkoeberAdresse;

    return $this;
  }

  /**
   * Get adresse1
   *
   * @return string
   */
  public function getMedkoeberAdresse() {
    return $this->medkoeberAdresse;
  }

  /**
   * Set land1
   *
   * @param string $medkoeberLand
   *
   * @return Grund
   */
  public function setMedkoeberLand($medkoeberLand) {
    $this->medkoeberLand = $medkoeberLand;

    return $this;
  }

  /**
   * Get land1
   *
   * @return string
   */
  public function getMedkoeberLand() {
    return $this->medkoeberLand;
  }

  /**
   * Set telefon1
   *
   * @param string $medkoeberTelefon
   *
   * @return Grund
   */
  public function setMedkoeberTelefon($medkoeberTelefon) {
    $this->medkoeberTelefon = $medkoeberTelefon;

    return $this;
  }

  /**
   * Get telefon1
   *
   * @return string
   */
  public function getMedkoeberTelefon() {
    return $this->medkoeberTelefon;
  }

  /**
   * Set mobil1
   *
   * @param string $medkoeberMobil
   *
   * @return Grund
   */
  public function setMedkoeberMobil($medkoeberMobil) {
    $this->medkoeberMobil = $medkoeberMobil;

    return $this;
  }

  /**
   * Get mobil1
   *
   * @return string
   */
  public function getMedkoeberMobil() {
    return $this->medkoeberMobil;
  }

  /**
   * Set medkoeberemail
   *
   * @param string $medkoeberEmail
   *
   * @return Grund
   */
  public function setMedkoeberEmail($medkoeberEmail) {
    $this->medkoeberEmail = $medkoeberEmail;

    return $this;
  }

  /**
   * Get medkoeberemail
   *
   * @return string
   */
  public function getMedkoeberEmail() {
    return $this->medkoeberEmail;
  }

  /**
   * Set notat
   *
   * @param string $koeberNotat
   *
   * @return Grund
   */
  public function setKoeberNotat($koeberNotat) {
    $this->koeberNotat = $koeberNotat;

    return $this;
  }

  /**
   * Get notat
   *
   * @return string
   */
  public function getKoeberNotat() {
    return $this->koeberNotat;
  }

  /**
   * Set medkoeberpostbyid
   *
   * @param \AppBundle\Entity\Postby $medkoeberPostby
   *
   * @return Grund
   */
  public function setMedkoeberPostby(\AppBundle\Entity\Postby $medkoeberPostby = NULL) {
    $this->medkoeberPostby = $medkoeberPostby;

    return $this;
  }

  /**
   * Get medkoeberpostbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getMedkoeberPostby() {
    return $this->medkoeberPostby;
  }

  /**
   * Set koeberpostbyid
   *
   * @param \AppBundle\Entity\Postby $koeberPostby
   *
   * @return Grund
   */
  public function setKoeberPostby(\AppBundle\Entity\Postby $koeberPostby = NULL) {
    $this->koeberPostby = $koeberPostby;

    return $this;
  }

  /**
   * Get koeberpostbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getKoeberPostby() {
    return $this->koeberPostby;
  }

  /**
   * @return mixed
   */
  public function getReservationer() {
    return $this->reservationer;
  }

  /**
   * @return mixed
   */
  public function getActiveReservationer() {
    $active = [];
    foreach ($this->getReservationer() as $reservation) {
      if(!$reservation->isAnnulleret()) {
        $active[] = $reservation;
      }
    }

    return $active;
  }

  /**
   * @param Reservation $reservation
   */
  public function addReservation(Reservation $reservation) {
    $this->reservationer->add($reservation);
  }

  /**
   * @param Reservation $reservation
   */
  public function removeReservation(Reservation $reservation) {
    $this->reservationer->removeElement($reservation);
  }

  /**
   * @param Interessent $interessent
   */
  public function addInteressent(Interessent $interessent) {
    $reservation = new Reservation();
    $reservation->setGrund($this);
    $reservation->setInteressent($interessent);

    $this->reservationer->add($reservation);
    $interessent->addReservation($reservation);
  }

  /**
   * @return mixed
   */
  public function getSalgshistorik() {
    return $this->salgshistorik;
  }

  /**
   * @param Salgshistorik $salgshistorik
   */
  public function addSalgshistorik(Salgshistorik $salgshistorik) {
    $salgshistorik->setGrund($this);
    $this->salgshistorik->add($salgshistorik);
  }

  /**
   * @param Salgshistorik $salgshistorik
   */
  public function removeSalgshistorik(Salgshistorik $salgshistorik) {
    $this->salgshistorik->removeElement($salgshistorik);
  }

  /**
   * Set lokalsamfund
   *
   * @param \AppBundle\Entity\Lokalsamfund $lokalsamfund
   *
   * @return Grund
   */
  public function setLokalsamfund(\AppBundle\Entity\Lokalsamfund $lokalsamfund = NULL) {
    $this->lokalsamfund = $lokalsamfund;

    return $this;
  }

  /**
   * Get lokalsamfund
   *
   * @return \AppBundle\Entity\Lokalsamfund
   */
  public function getLokalsamfund() {
    return $this->lokalsamfund;
  }

  /**
   * Set salgsomraade
   *
   * @param Salgsomraade|NULL $salgsomraade
   *
   * @return $this
   */
  public function setSalgsomraade(\AppBundle\Entity\Salgsomraade $salgsomraade = NULL) {
    $this->salgsomraade = $salgsomraade;

    return $this;
  }

  /**
   * Get salgsomraade entity.
   *
   * @return \AppBundle\Entity\Salgsomraade
   */
  public function getSalgsomraade() {
    return $this->salgsomraade;
  }

  /**
   * @return \CrEOF\Spatial\DBAL\Types\Geography
   */
  public function getSpGeometry() {
    return $this->spGeometry;
  }

  /**
   * @param \CrEOF\Spatial\PHP\Types\Geography\Polygon $spGeometry
   */
  public function setSpGeometry(\CrEOF\Spatial\PHP\Types\Geography\Polygon $spGeometry) {
    $this->spGeometry = $spGeometry;
  }

  /**
   * @return int
   */
  public function getSrid() {
    return $this->srid;
  }

  /**
   * @param int $srid
   */
  public function setSrid($srid) {
    $this->srid = $srid;
  }

  /**
   * @return string
   */
  public function getPdfLink() {
    return $this->pdfLink;
  }

  /**
   * @param string $pdfLink
   */
  public function setPdfLink($pdfLink) {
    $this->pdfLink = $pdfLink;
  }

  /**
   * Get the spatial data as an array.
   *
   * @return null|array
   *   If spatial data exists on the entity array is returned else null.
   */
  public function getSpGeometryArray() {
    if ($this->getSpGeometry()) {
      $json['type'] = $this->getSpGeometry()->getType();
      $json['coordinates'] = $this->getSpGeometry()->toArray();

      return $json;
    }

    return NULL;
  }

  /**
   * Get the status to show in the admin interface
   *
   * "Copy-paste" from legacy system
   *
   * @return string
   */
  public function getVisStatus() {
    if ($this->getSalgstatus() === GrundSalgStatus::LEDIG) {
      $status = strval($this->getStatus());
    } else {
      $status = strval($this->getSalgstatus());
    }

    return $status;
  }

  /**
   * Clear all 'date' and 'koeber' fields
   */
  public function clearSalgFields() {
    $this->setResstart(NULL);
    $this->setResslut(NULL);
    $this->setTilbudstart(NULL);
    $this->setTilbudslut(NULL);
    $this->setAccept(NULL);
    $this->setOvertagelse(NULL);
    $this->setSkoederekv(NULL);
    $this->setBeloebanvist(NULL);
    $this->setAntagetbud(NULL);

    $this->setKoeberNavn(NULL);
    $this->setKoeberAdresse(NULL);
    $this->setKoeberPostby(NULL);
    $this->setKoeberLand(NULL);
    $this->setKoeberTelefon(NULL);
    $this->setKoeberMobil(NULL);
    $this->setKoeberEmail(NULL);

    $this->setKoeberNotat(NULL);

    $this->setMedkoeberNavn(NULL);
    $this->setMedkoeberAdresse(NULL);
    $this->setMedkoeberPostby(NULL);
    $this->setMedkoeberLand(NULL);
    $this->setMedkoeberTelefon(NULL);
    $this->setMedkoeberMobil(NULL);
    $this->setMedkoeberEmail(NULL);
  }

  /**
   * Set all 'salgs' fields based on given Interessent
   *
   * @param Interessent $interessent
   */
  public function setSaleFromInteressent(Interessent $interessent) {
    $this->setResstart($interessent->getCreatedAt());

    $this->setKoeberNavn($interessent->getKoeberNavn());
    $this->setKoeberAdresse($interessent->getKoeberAdresse());
    $this->setKoeberPostby($interessent->getKoeberPostby());
    $this->setKoeberLand($interessent->getKoeberLand());
    $this->setKoeberTelefon($interessent->getKoeberTelefon());
    $this->setKoeberMobil($interessent->getKoeberMobil());
    $this->setKoeberEmail($interessent->getKoeberEmail());

    $this->setKoeberNotat($interessent->getKoeberNotat());

    $this->setMedkoeberNavn($interessent->getMedkoeberNavn());
    $this->setMedkoeberAdresse($interessent->getMedkoeberAdresse());
    $this->setMedkoeberPostby($interessent->getMedkoeberPostby());
    $this->setMedkoeberLand($interessent->getMedkoeberLand());
    $this->setMedkoeberTelefon($interessent->getMedkoeberTelefon());
    $this->setMedkoeberMobil($interessent->getMedkoeberMobil());
    $this->setMedkoeberEmail($interessent->getMedkoeberEmail());
  }
}
