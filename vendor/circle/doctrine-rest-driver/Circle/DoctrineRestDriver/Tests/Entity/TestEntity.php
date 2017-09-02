<?php
/**
 * This file is part of DoctrineRestDriver.
 *
 * DoctrineRestDriver is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DoctrineRestDriver is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DoctrineRestDriver.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Circle\DoctrineRestDriver\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This is a test entity
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class TestEntity {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    protected $value;

    /**
     * @ORM\OneToMany(targetEntity="Circle\DoctrineRestDriver\Tests\Entity\AssociatedEntity", mappedBy="product")
     * @var ArrayCollection
     */
    protected $categories;

    /**
     * TestEntity constructor
     */
    public function __construct() {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param  int        $id
     * @return TestEntity
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param  string     $name
     * @return TestEntity
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param  string     $value
     * @return TestEntity
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * @param  AssociatedEntity $category
     * @return TestEntity
     */
    public function addCategory(AssociatedEntity $category) {
        $this->categories->add($category);
        return $this;
    }

    /**
     * @param  Collection $categories
     * @return TestEntity
     */
    public function setCategories(Collection $categories) {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories() {
        return $this->categories;
    }
}