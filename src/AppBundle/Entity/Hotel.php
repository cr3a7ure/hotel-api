<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A hotel is an establishment that provides lodging paid on a short-term basis (Source: Wikipedia, the free encyclopedia, see http://en.wikipedia.org/wiki/Hotel).
 *
 * See also the [dedicated document on the use of schema.org for marking up hotels and other forms of accommodations](/docs/hotels.html).
 *
 * @see http://schema.org/Hotel Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(type="http://schema.org/Hotel",
 *             iri="http://schema.org/Hotel",
 *             attributes={"filters"={"hotel.search"},
 *                     "normalization_context"={"groups"={"readHotel"}},
 *                     "denormalization_context"={"groups"={"writeHotel"}}
 *             },
 *             collectionOperations={
 *                 "get"={"method"="GET",
 *                        "hydra_context"={"@type"="schema:SearchAction",
 *                                         "schema:target"="/hotels",
 *                                         "schema:query"={"@type"="vocab:#GeoCoordinates"},
 *                                         "schema:result"="vocab:#Hotel",
 *                                         "schema:object"="vocab:#Hotel"
 *                                         }},
 *                 "post"={"method"="POST"}
 *             }
 *             )
 */
class Hotel
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
     * @var string The name of the item
     *
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/name")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $name;

    /**
     * @var string A description of the item
     *
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/description")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $description;

    /**
     * @var PostalAddress Physical address of the item
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PostalAddress")
     * @ApiProperty(iri="http://schema.org/address")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $address;

    /**
     * @var Rating An official rating for a lodging business or food establishment, e.g. from national associations or standards bodies. Use the author property to indicate the rating organization, e.g. as an Organization with name such as (e.g. HOTREC, DEHOGA, WHR, or Hotelstars)
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rating")
     * @ApiProperty(iri="http://schema.org/starRating")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $starRating;

    /**
     * @var string The fax number
     *
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/faxNumber")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $faxNumber;

    /**
     * @var string The telephone number
     *
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/telephone")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $telephone;

    /**
     * @var Offer A pointer to products or services offered by the organization or person
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Offer")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ApiProperty(iri="http://schema.org/makesOffer")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $makesOffer;

    /**
     * @var PriceSpecification The price range of the business, for example ```$$$```
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\PriceSpecification")
     * @ApiProperty(iri="http://schema.org/priceRange")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $priceRange;

    /**
     * @var string An award won by or for this item
     *
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/award")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $award;

    /**
     * @var GeoCoordinates
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\GeoCoordinates")
     * @ORM\JoinColumn(nullable=false)
     * @ApiProperty(iri="http://schema.org/geo")
     * @Assert\NotNull
     * @Groups({"readHotel", "writeHotel"})
     */
    private $geo;

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
     * Sets name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets description.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets address.
     *
     * @param PostalAddress $address
     *
     * @return $this
     */
    public function setAddress(PostalAddress $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Gets address.
     *
     * @return PostalAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets starRating.
     *
     * @param Rating $starRating
     *
     * @return $this
     */
    public function setStarRating(Rating $starRating = null)
    {
        $this->starRating = $starRating;

        return $this;
    }

    /**
     * Gets starRating.
     *
     * @return Rating
     */
    public function getStarRating()
    {
        return $this->starRating;
    }

    /**
     * Sets faxNumber.
     *
     * @param string $faxNumber
     *
     * @return $this
     */
    public function setFaxNumber($faxNumber)
    {
        $this->faxNumber = $faxNumber;

        return $this;
    }

    /**
     * Gets faxNumber.
     *
     * @return string
     */
    public function getFaxNumber()
    {
        return $this->faxNumber;
    }

    /**
     * Sets telephone.
     *
     * @param string $telephone
     *
     * @return $this
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Gets telephone.
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Sets makesOffer.
     *
     * @param Offer $makesOffer
     *
     * @return $this
     */
    public function setMakesOffer(Offer $makesOffer = null)
    {
        $this->makesOffer = $makesOffer;

        return $this;
    }

    /**
     * Gets makesOffer.
     *
     * @return Offer
     */
    public function getMakesOffer()
    {
        return $this->makesOffer;
    }

    /**
     * Sets priceRange.
     *
     * @param PriceSpecification $priceRange
     *
     * @return $this
     */
    public function setPriceRange(PriceSpecification $priceRange = null)
    {
        $this->priceRange = $priceRange;

        return $this;
    }

    /**
     * Gets priceRange.
     *
     * @return PriceSpecification
     */
    public function getPriceRange()
    {
        return $this->priceRange;
    }

    /**
     * Sets award.
     *
     * @param string $award
     *
     * @return $this
     */
    public function setAward($award)
    {
        $this->award = $award;

        return $this;
    }

    /**
     * Gets award.
     *
     * @return string
     */
    public function getAward()
    {
        return $this->award;
    }

    /**
     * Sets geo.
     *
     * @param GeoCoordinates $geo
     *
     * @return $this
     */
    public function setGeo(GeoCoordinates $geo)
    {
        $this->geo = $geo;

        return $this;
    }

    /**
     * Gets geo.
     *
     * @return GeoCoordinates
     */
    public function getGeo()
    {
        return $this->geo;
    }
}
