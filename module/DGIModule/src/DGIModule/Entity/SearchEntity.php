<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentThumb
 *
 * @ORM\Entity
 */
class SearchEntity
{

    /**
     * @ORM\Column(type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $uuid;

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return SearchEntity
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @ORM\Column(type="string")
     *
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $img;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $imgTitle;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $description;
    /**
     * @ORM\Column(type="integer")
     *
     */
    private $level;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $cityName;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $postalCode;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $regionName;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $countryName;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $username;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $userUUID;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $userPicture;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    private $date;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return SearchEntity
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param string $img
     * @return SearchEntity
     */
    public function setImg($img)
    {
        $this->img = $img;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SearchEntity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return SearchEntity
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return SearchEntity
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return SearchEntity
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserUUID()
    {
        return $this->userUUID;
    }

    /**
     * @param mixed $userUUID
     * @return SearchEntity
     */
    public function setUserUUID($userUUID)
    {
        $this->userUUID = $userUUID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImgTitle()
    {
        return $this->imgTitle;
    }

    /**
     * @param mixed $imgTitle
     * @return SearchEntity
     */
    public function setImgTitle($imgTitle)
    {
        $this->imgTitle = $imgTitle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserPicture()
    {
        return $this->userPicture;
    }

    /**
     * @param mixed $userPicture
     * @return SearchEntity
     */
    public function setUserPicture($userPicture)
    {
        $this->userPicture = $userPicture;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     * @return SearchEntity
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * @param mixed $cityName
     * @return SearchEntity
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     * @return SearchEntity
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegionName()
    {
        return $this->regionName;
    }

    /**
     * @param mixed $regionName
     * @return SearchEntity
     */
    public function setRegionName($regionName)
    {
        $this->regionName = $regionName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @param mixed $countryName
     * @return SearchEntity
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
        return $this;
    }

}
