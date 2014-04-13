<?php

namespace App\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 */
class User
{
    /**
    * @var integer
    */
    private $id;

    /**
    * @var integer
    */
    private $userId;

    /**
    * @var boolean
    */
    private $gender;

    /**
    * @var string
    */
    private $birthday;

    /**
    * @var string
    */
    private $locationCountry;

    /**
    * @var string
    */
    private $country;

    /**
    * @var integer
    */
    private $sessionId;

    /**
    * Get id
    *
    * @return integer
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Set userId
    *
    * @param integer $userId
    * @return UserInfo
    */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
    * Get userId
    *
    * @return integer
    */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
    * Set gender
    *
    * @param boolean $gender
    * @return UserInfo
    */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
    * Get gender
    *
    * @return boolean
    */
    public function getGender()
    {
        return $this->gender;
    }

    /**
    * Set birthday
    *
    * @param string $birthday
    * @return UserInfo
    */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
    * Get birthday
    *
    * @return string
    */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
    * Set locationCountry
    *
    * @param string $locationCountry
    * @return UserInfo
    */
    public function setLocationCountry($locationCountry)
    {
        $this->locationCountry = $locationCountry;

        return $this;
    }

    /**
    * Get locationCountry
    *
    * @return string
    */
    public function getLocationCountry()
    {
        return $this->locationCountry;
    }

    /**
    * Set country
    *
    * @param string $country
    * @return UserInfo
    */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
    * Get country
    *
    * @return string
    */
    public function getCountry()
    {
        return $this->country;
    }

    /**
    * Set sessionId
    *
    * @param integer $sessionId
    * @return BuyDiscountPack
    */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
    * Get sessionId
    *
    * @return integer
    */
    public function getSessionId()
    {
        return $this->sessionId;
    }
}