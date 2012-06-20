<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Stamp\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Implements \SdsCommon\Stamp\UpdatedOnInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait UpdatedOnTrait {

    /**
     * @ODM\Field(type="timestamp")
     */
    protected $updatedOn;

    /**
     *
     * @param timestamp $timestamp
     */
    public function setUpdatedOn($timestamp){
        $this->updatedOn = $timestamp;
    }

    /**
     *
     * @return timestamp
     */
    public function getUpdatedOn(){
        return $this->updatedOn;
    }
}
