<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\User\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Implementation of Sds\Common\Auth\AuthInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait AuthTrait {

    /**
     * @ODM\Field(type="string")
     * @Sds\Serializer(@Sds\Ignore)
     * @Sds\Dojo(
     *     @Sds\Metadata({
     *         "type" = "password"
     *     }),
     *     @Sds\ValidatorGroup(
     *         @Sds\Required,
     *         @Sds\Validator(class = "Sds/Common/Validator/PasswordValidator")
     *     )
     * )
     * @Sds\ValidatorGroup(
     *     @Sds\Required,
     *     @Sds\Validator(class = "Sds\Common\Validator\PasswordValidator")
     * )
     * @Sds\CryptHash
     */
    protected $password;

    /**
     * Returns encrypted password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     *
     * @param string $plaintext
     */
    public function setPassword($plaintext) {
        $this->password = (string) $plaintext;
    }
}
