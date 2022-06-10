<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="bl_facture", indexes={@ORM\Index(name="IDX_EB71D8F5979B1AD6", columns={"company_id"}),
 * @ORM\Index(name="IDX_EB71D8F561220EA6", columns={"creator_id"}),
 * @ORM\Index(name="IDX_EB71D8F5A9CED711", columns={"updator_id"}),
 * @ORM\Index(name="IDX_EB71D8F519EB6921", columns={"client_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\FactureRepository")
 */
class Facture extends \SSH\MsJwtBundle\Entity\AbstractEntity
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="bl_facture_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="guid", nullable=false, options={"default"="uuid_generate_v4()"})
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=false)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false, options={"default"="DRAFT"})
     */
    private $status = 'DRAFT';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="invoice_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $invoiceDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="datetime", nullable=false)
     */
    private $dueDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="discount_total", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $discountTotal;

    /**
     * @var string|null
     *
     * @ORM\Column(name="company_mail", type="string", length=200, nullable=true)
     */
    private $companyMail;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=255, nullable=false)
     */
    private $companyName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="company_address", type="string", length=300, nullable=true)
     */
    private $companyAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="company_zipcode", type="string", length=15, nullable=false)
     */
    private $companyZipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="company_city", type="string", length=15, nullable=false)
     */
    private $companyCity;

    /**
     * @var string
     *
     * @ORM\Column(name="client_name", type="string", length=255, nullable=false)
     */
    private $clientName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="client_address", type="string", length=300, nullable=true)
     */
    private $clientAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="client_zipcode", type="string", length=15, nullable=false)
     */
    private $clientZipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="client_city", type="string", length=15, nullable=false)
     */
    private $clientCity;

    /**
     * @var string
     *
     * @ORM\Column(name="head", type="string", length=100, nullable=false, options={"default"="FACTURE"})
     */
    private $head = 'FACTURE';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="discount", type="boolean", nullable=true)
     */
    private $discount;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="discount_on_total", type="boolean", nullable=true)
     */
    private $discountOnTotal;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="discount_fixed_value", type="boolean", nullable=true)
     */
    private $discountFixedValue = false;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="discount_base_ttc", type="boolean", nullable=false)
     */
    private $discountBaseTtc;

    /**
     * @var string
     *
     * @ORM\Column(name="acompte", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00"})
     */
    private $acompte = '0.00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \Company
     *
     * @ORM\ManyToOne(targetEntity="Company", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * })
     */
    private $creator;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updator_id", referencedColumnName="id")
     * })
     */
    private $updator;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="Currency", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     * })
     */
    private $currency;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language"), fetch="EAGER"
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->code = \SSH\MsJwtBundle\Utils\MyTools::GUIDv4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

   /* public function getPreNote(): ?string
    {
        return $this->preNote;
    }

    public function setPreNote(?string $preNote): self
    {
        $this->preNote = $preNote;

        return $this;
    }*/

  /*  public function getPostNote(): ?string
    {
        return $this->postNote;
    }

    public function setPostNote(?string $postNote): self
    {
        $this->postNote = $postNote;

        return $this;
    }*/

    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoiceDate;
    }

    public function setInvoiceDate(\DateTimeInterface $invoiceDate): self
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getDiscountTotal(): ?string
    {
        return $this->discountTotal;
    }

    public function setDiscountTotal(?string $discountTotal): self
    {
        $this->discountTotal = $discountTotal;

        return $this;
    }

    public function getCompanyMail(): ?string
    {
        return $this->companyMail;
    }

    public function setCompanyMail(?string $companyMail): self
    {
        $this->companyMail = $companyMail;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyAddress(): ?string
    {
        return $this->companyAddress;
    }

    public function setCompanyAddress(?string $companyAddress): self
    {
        $this->companyAddress = $companyAddress;

        return $this;
    }

    public function getCompanyZipcode(): ?string
    {
        return $this->companyZipcode;
    }

    public function setCompanyZipcode(string $companyZipcode): self
    {
        $this->companyZipcode = $companyZipcode;

        return $this;
    }

    public function getCompanyCity(): ?string
    {
        return $this->companyCity;
    }

    public function setCompanyCity(string $companyCity): self
    {
        $this->companyCity = $companyCity;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): self
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getClientAddress(): ?string
    {
        return $this->clientAddress;
    }

    public function setClientAddress(?string $clientAddress): self
    {
        $this->clientAddress = $clientAddress;

        return $this;
    }

    public function getClientZipcode(): ?string
    {
        return $this->clientZipcode;
    }

    public function setClientZipcode(string $clientZipcode): self
    {
        $this->clientZipcode = $clientZipcode;

        return $this;
    }

    public function getClientCity(): ?string
    {
        return $this->clientCity;
    }

    public function setClientCity(string $clientCity): self
    {
        $this->clientCity = $clientCity;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getUpdator(): ?User
    {
        return $this->updator;
    }

    public function setUpdator(?User $updator): self
    {
        $this->updator = $updator;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getHead(): ?string
    {
        return $this->head;
    }

    public function setHead(string $head): self
    {
        $this->head = $head;

        return $this;
    }

    public function getDiscount(): ?bool
    {
        return $this->discount;
    }

    public function setDiscount(?bool $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscountOnTotal(): ?bool
    {
        return $this->discountOnTotal;
    }

    public function setDiscountOnTotal(?bool $discountOnTotal): self
    {
        $this->discountOnTotal = $discountOnTotal;

        return $this;
    }

    public function getDiscountFixedValue(): ?bool
    {
        return $this->discountFixedValue;
    }

    public function setDiscountFixedValue(?bool $discountFixedValue): self
    {
        $this->discountFixedValue = $discountFixedValue;

        return $this;
    }

    public function getDiscountBaseTtc(): ?bool
    {
        return $this->discountBaseTtc;
    }

    public function setDiscountBaseTtc(bool $discountBaseTtc): self
    {
        $this->discountBaseTtc = $discountBaseTtc;

        return $this;
    }
    public function getAcompte(): ?string
    {
        return $this->acompte;
    }

    public function setAcompte(?string $acompte): self
    {
        $this->acompte = $acompte;

        return $this;
    }


    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }




}
