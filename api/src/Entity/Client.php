<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * BlClient
 *
 * @ORM\Table(name="bl_client", indexes={@ORM\Index(name="IDX_B0C334D0979B1AD6", columns={"company_id"}), @ORM\Index(name="IDX_B0C334D0F92F3E70", columns={"country_id"}), @ORM\Index(name="IDX_B0C334D0EE8724AC", columns={"payment_condition_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client extends \SSH\MsJwtBundle\Entity\AbstractEntity
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="bl_client_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="type", type="string", nullable=false, options={"default"="PARTICULAR"})
     */
    private $type = 'PARTICULAR';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=70, nullable=false)
     */
    private $firstname;


    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=70, nullable=false)
     */
    private $lastname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="registry_number", type="string", length=255, nullable=true)
     */
    private $registryNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="vat_number", type="string", length=255, nullable=true)
     */
    private $vatNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=300, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=15, nullable=false)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=15, nullable=false)
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mail", type="string", length=200, nullable=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15, nullable=false)
     */
    private $phone;

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
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="Company", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

    /**
     * @var PaymentCondition
     *
     * @ORM\ManyToOne(targetEntity="PaymentCondition", fetch="EAGER")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="payment_condition_id", referencedColumnName="id")
     * })
     */
    private $paymentCondition;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }


    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getRegistryNumber(): ?string
    {
        return $this->registryNumber;
    }

    public function setRegistryNumber(?string $registryNumber): self
    {
        $this->registryNumber = $registryNumber;

        return $this;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function setVatNumber(?string $vatNumber): self
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPaymentCondition(): ?PaymentCondition
    {
        return $this->paymentCondition;
    }

    public function setPaymentCondition(?PaymentCondition $paymentCondition): self
    {
        $this->paymentCondition = $paymentCondition;

        return $this;
    }

    public function getClientInfo(): array
    {
        return([
            'client' => $this,
            'clientName' => $this->getName(),
            'clientAddress' => $this->getAddress(),
            'clientZipcode' => $this->getZipcode(),
            'clientCity' => $this->getCity()
        ]);

    }

}
