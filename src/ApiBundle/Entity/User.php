<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="user_login", columns={"user_login"}), @ORM\UniqueConstraint(name="user_mail", columns={"user_mail"})}, indexes={@ORM\Index(name="user_activate_key", columns={"user_activate_key"}), @ORM\Index(name="user_activate", columns={"user_activate"}), @ORM\Index(name="user_rating", columns={"user_rating"}), @ORM\Index(name="user_profile_sex", columns={"user_profile_sex"})})
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_id", type="integer", length=11, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_login", type="string", length=30, nullable=false)
     */
    protected $login;

    /**
     * @var string
     *
     * @ORM\Column(name="user_password", type="string", length=50, nullable=false)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="user_mail", type="string", length=50, nullable=true)
     */
    protected $mail;

    /**
     * @var float
     * @JMS\Type("float")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_skill", type="float", precision=9, scale=3, nullable=false, columnDefinition="FLOAT(9,3) NOT NULL")
     */
    protected $skill = '0.000';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="user_date_register", type="datetime", nullable=false)
     */
    protected $dateRegister;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="user_date_activate", type="datetime", nullable=true)
     */
    protected $dateActivate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="user_date_comment_last", type="datetime", nullable=true)
     */
    protected $dateCommentLast;

    /**
     * @var string
     *
     * @ORM\Column(name="user_ip_register", type="string", length=20, nullable=false)
     */
    protected $ipRegister;

    /**
     * @var float
     * @JMS\Type("float")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_rating", type="float", precision=9, scale=3, nullable=false)
     */
    protected $rating = '0.000';

    /**
     * @var integer
     *
     * @ORM\Column(name="user_count_vote", type="integer", nullable=false)
     */
    protected $countVote = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="user_activate", type="boolean", nullable=false)
     */
    protected $activate = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="user_activate_key", type="string", length=32, nullable=true)
     */
    protected $activateKey;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_profile_name", type="string", length=50, nullable=true)
     */
    protected $profileName;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_profile_sex", type="string", nullable=false)
     */
    protected $profileSex = 'other';

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_profile_country", type="string", length=30, nullable=true)
     */
    protected $profileCountry;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_profile_region", type="string", length=30, nullable=true)
     */
    protected $profileRegion;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_profile_city", type="string", length=30, nullable=true)
     */
    protected $profileCity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="user_profile_birthday", type="datetime", nullable=true)
     */
    protected $profileBirthday;

    /**
     * @var string
     *
     * @ORM\Column(name="user_profile_site", type="string", length=200, nullable=true)
     */
    protected $profileSite;

    /**
     * @var string
     *
     * @ORM\Column(name="user_profile_site_name", type="string", length=50, nullable=true)
     */
    protected $profileSiteName;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_profile_icq", type="bigint", nullable=true)
     */
    protected $profileIcq;

    /**
     * @var string
     *
     * @ORM\Column(name="user_profile_about", type="text", length=65535, nullable=true)
     */
    protected $profileAbout;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="user_profile_date", type="datetime", nullable=true)
     */
    protected $profileDate;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_profile_avatar", type="string", length=250, nullable=true)
     */
    protected $profileAvatar;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_profile_foto", type="string", length=250, nullable=true)
     */
    protected $profileFoto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="user_settings_notice_new_topic", type="boolean", nullable=false)
     */
    protected $settingsNoticeNewTopic = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="user_settings_notice_new_comment", type="boolean", nullable=false)
     */
    protected $settingsNoticeNewComment = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="user_settings_notice_new_talk", type="boolean", nullable=false)
     */
    protected $settingsNoticeNewTalk = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="user_settings_notice_reply_comment", type="boolean", nullable=false)
     */
    protected $settingsNoticeReplyComment = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="user_settings_notice_new_friend", type="boolean", nullable=false)
     */
    protected $settingsNoticeNewFriend = '1';

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Groups({"user"})
     * @ORM\Column(name="user_settings_timezone", type="string", length=6, nullable=true)
     */
    protected $settingsTimezone;

    /**
     * @var ArrayCollection|Client[]
     * @JMS\Groups({"user-clients"})
     * @JMS\Type("ArrayCollection<Client>")
     * @ORM\ManyToMany(targetEntity="Client", mappedBy="users")
     */
    protected $clients;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->login;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function setLastLogin($date)
    {
        // nothing
    }

    public function getClients()
    {
        return $this->clients;
    }

    public function addClient(Client $client)
    {
        $this->clients->add($client);
    }

    public function removeClient(Client $client)
    {
        $this->clients->remove($client);
    }
}

