<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A structured value representing a price or price range. Typically, only the subclasses of this type are used for markup. It is recommended to use \[\[MonetaryAmount\]\] to describe independent amounts of money such as a salary, credit card limits, etc.
 *
 * @see http://schema.org/PriceSpecification Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(type="http://schema.org/PriceSpecification",
 *             iri="http://schema.org/PriceSpecification",
 *             attributes={
 *             }
 *             )
 */
class PriceSpecification
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $id;

    /**
     * @var float The highest price if the price is a range
     *
     * @ORM\Column(type="float")
     * @Assert\Type(type="float")
     * @Assert\NotNull
     * @ApiProperty(iri="http://schema.org/maxPrice")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $maxPrice;

    /**
     * @var float The lowest price if the price is a range
     *
     * @ORM\Column(type="float")
     * @Assert\Type(type="float")
     * @Assert\NotNull
     * @ApiProperty(iri="http://schema.org/minPrice")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $minPrice;

    /**
     * @var string The currency (in 3-letter ISO 4217 format) of the price or a price component, when attached to \[\[PriceSpecification\]\] and its subtypes
     *
     * @ORM\Column
     * @Assert\Type(type="string")
     * @Assert\NotNull
     * @ApiProperty(iri="http://schema.org/priceCurrency")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $priceCurrency;

    /**
     * Sets id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets maxPrice.
     *
     * @param float $maxPrice
     *
     * @return $this
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Gets maxPrice.
     *
     * @return float
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Sets minPrice.
     *
     * @param float $minPrice
     *
     * @return $this
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * Gets minPrice.
     *
     * @return float
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * Sets priceCurrency.
     *
     * @param string $priceCurrency
     *
     * @return $this
     */
    public function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;

        return $this;
    }

    /**
     * Gets priceCurrency.
     *
     * @return string
     */
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
    }
}
