<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuoteConfig
 *
 * @ORM\Table(name="bl_quote_config", indexes={@ORM\Index(name="IDX_205E4C8538248176", columns={"currency_id"}),
 * @ORM\Index(name="IDX_205E4C8582F1BAF4", columns={"language_id"}),
 * @ORM\Index(name="IDX_205E4C85DB805178", columns={"quote_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\QuoteConfigRepository")
 */
class QuoteConfig extends \SSH\MsJwtBundle\Entity\AbstractEntity
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="bl_quote_config_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="head", type="string", length=100, nullable=false, options={"default"="DEVIS"})
     */
    private $head = 'DEVIS';

    /**
     * @var string
     *
     * @ORM\Column(name="total_line", type="string", nullable=false, options={"default"="TTC"})
     */
    private $totalLine = 'TTC';

    /**
     * @var bool
     *
     * @ORM\Column(name="discount", type="boolean", nullable=false)
     */
    private $discount = false;

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
    private $discountFixedValue;

    /**
     * @var string
     *
     * @ORM\Column(name="discount_base_ttc", type="string", nullable=false, options={"default"="TTC"})
     */
    private $discountBaseTtc = 'TTC';

    /**
     * @var \Currency
     *
     * @ORM\ManyToOne(targetEntity="Currency", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     * })
     */
    private $currency;

    /**
     * @var \Language
     *
     * @ORM\ManyToOne(targetEntity="Language"), fetch="EAGER"
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;

    /**
     * @var \Quote
     *
     * @ORM\ManyToOne(targetEntity="Quote", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quote_id", referencedColumnName="id")
     * })
     */
    private $quote;

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

    public function getHead(): ?string
    {
        return $this->head;
    }

    public function setHead(string $head): self
    {
        $this->head = $head;

        return $this;
    }

    public function getTotalLine(): ?string
    {
        return $this->totalLine;
    }

    public function setTotalLine(string $totalLine): self
    {
        $this->totalLine = $totalLine;

        return $this;
    }

    public function getDiscount(): ?bool
    {
        return $this->discount;
    }

    public function setDiscount(bool $discount): self
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

    public function getDiscountBaseTtc(): ?string
    {
        return $this->discountBaseTtc;
    }

    public function setDiscountBaseTtc(string $discountBaseTtc): self
    {
        $this->discountBaseTtc = $discountBaseTtc;

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

    public function getQuote(): ?Quote
    {
        return $this->quote;
    }

    public function setQuote(?Quote $quote): self
    {
        $this->quote = $quote;

        return $this;
    }

}
