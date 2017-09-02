<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A rating is an evaluation on a numeric scale, such as 1 to 5 stars.
 *
 * @see http://schema.org/Rating Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(type="http://schema.org/Rating", iri="http://schema.org/Rating")
 */
class Rating
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string The author of this content or rating. Please note that author is special in that HTML 5 provides a special mechanism for indicating authorship via the rel tag. That is equivalent to this and may be used interchangeably
     *
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/author")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $author;

    /**
     * @var float The highest value allowed in this rating system. If bestRating is omitted, 5 is assumed
     *
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type(type="float")
     * @ApiProperty(iri="http://schema.org/bestRating")
     */
    private $bestRating;

    /**
     * @var float The rating for the content
     *
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type(type="float")
     * @ApiProperty(iri="http://schema.org/ratingValue")
     * @Groups({"readHotel", "writeHotel"})
     */
    private $ratingValue;

    /**
     * @var float The lowest value allowed in this rating system. If worstRating is omitted, 1 is assumed
     *
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type(type="float")
     * @ApiProperty(iri="http://schema.org/worstRating")
     */
    private $worstRating;

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
     * Sets author.
     *
     * @param string $author
     *
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Gets author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets bestRating.
     *
     * @param float $bestRating
     *
     * @return $this
     */
    public function setBestRating($bestRating)
    {
        $this->bestRating = $bestRating;

        return $this;
    }

    /**
     * Gets bestRating.
     *
     * @return float
     */
    public function getBestRating()
    {
        return $this->bestRating;
    }

    /**
     * Sets ratingValue.
     *
     * @param float $ratingValue
     *
     * @return $this
     */
    public function setRatingValue($ratingValue)
    {
        $this->ratingValue = $ratingValue;

        return $this;
    }

    /**
     * Gets ratingValue.
     *
     * @return float
     */
    public function getRatingValue()
    {
        return $this->ratingValue;
    }

    /**
     * Sets worstRating.
     *
     * @param float $worstRating
     *
     * @return $this
     */
    public function setWorstRating($worstRating)
    {
        $this->worstRating = $worstRating;

        return $this;
    }

    /**
     * Gets worstRating.
     *
     * @return float
     */
    public function getWorstRating()
    {
        return $this->worstRating;
    }
}
