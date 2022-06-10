<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureProduct
 *
 * @ORM\Table(name="bl_facture_product", indexes={@ORM\Index(name="IDX_17DF9A76F8BD700D", columns={"unity_id"}),
 * @ORM\Index(name="IDX_17DF9A76B5B63A6B", columns={"vat_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\FactureProductRepository")
 */
class FactureProduct extends \SSH\MsJwtBundle\Entity\AbstractEntity
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="bl_facture_product_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="porder", type="string", length=255, nullable=false)
     */
    private $porder;

    /**
     * @var string
     *
     * @ORM\Column(name="unit_price", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00"})
     */
    private $unitPrice = '0.00';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="discount_fixed_value", type="boolean", nullable=true)
     */
    private $discountFixedValue;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2, nullable=false, options={"default"="0.00"})
     */
    private $amount = '0.00';

    /**
     * @var string|null
     *
     * @ORM\Column(name="discount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="vat_value", type="string", length=25, nullable=false)
     */
    private $vatValue;

    /**
     * @var string
     *
     * @ORM\Column(name="unity_value", type="string", length=25, nullable=false)
     */
    private $unityValue;

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
     * @var Unity
     *
     * @ORM\ManyToOne(targetEntity="Unity", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unity_id", referencedColumnName="id")
     * })
     */
    private $unity;

    /**
     * @var Vat
     *
     * @ORM\ManyToOne(targetEntity="Vat", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vat_id", referencedColumnName="id")
     * })
     */
    private $vat;

    /**
     * @var Facture
     *
     * @ORM\ManyToOne(targetEntity="Facture", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facture_id", referencedColumnName="id" )
     * })
     */
    private $facture;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPorder(): ?string
    {
        return $this->porder;
    }

    public function setPorder(string $porder): self
    {
        $this->porder = $porder;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(string $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(?string $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getVatValue(): ?string
    {
        return $this->vatValue;
    }

    public function setVatValue(string $vatValue): self
    {
        $this->vatValue = $vatValue;

        return $this;
    }

    public function getUnityValue(): ?string
    {
        return $this->unityValue;
    }

    public function setUnityValue(string $unityValue): self
    {
        $this->unityValue = $unityValue;

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

    public function getUnity(): ?Unity
    {
        return $this->unity;
    }

    public function setUnity(?Unity $unity): self
    {
        $this->unity = $unity;

        return $this;
    }

    public function getVat(): ?Vat
    {
        return $this->vat;
    }

    public function setVat(?Vat $vat): self
    {

        $this->vat = $vat;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

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

}
