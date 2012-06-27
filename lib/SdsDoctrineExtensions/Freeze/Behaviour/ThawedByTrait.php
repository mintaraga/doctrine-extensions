<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \SdsCommon\Freeze\ThawedByInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait ThawedByTrait {

    /**
     * @ODM\Field(type="string")
     */
    protected $thawedBy;

    /**
     *
     * @param string $username
     */
    public function setThawedBy($username){
        $this->thawedBy = (string) $username;
    }

    /**
     *
     * @return string
     */
    public function getThawedBy(){
        return $this->thawedBy;
    }
}