<?php
/**
 * Created by PhpStorm.
 * User: yavlados
 * Date: 08.11.2017
 * Time: 12:04
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * One User has Many Vants .
     * @OneToMany(targetEntity="Vant", mappedBy="user")
     * @ORM\OrderBy({"createdAt" : "DESC"})
     */
    protected $vants;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->vants = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|Vant[]
     */
    public function getVants()
    {
        return $this->vants;
    }
}