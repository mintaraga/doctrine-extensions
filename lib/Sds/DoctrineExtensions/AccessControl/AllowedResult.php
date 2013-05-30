<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

/**
 * Implements Sds\Common\AccessControl\PermissionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AllowedResult
{

    protected $allowed;

    protected $old;

    protected $new;

    public function getAllowed() {
        return $this->allowed;
    }

    public function setAllowed($allowed) {
        $this->allowed = (boolean) $allowed;
    }

    public function getOld() {
        return $this->old;
    }

    public function setOld(array $old) {
        $this->old = $old;
    }

    public function getNew() {
        return $this->new;
    }

    public function setNew(array $new) {
        $this->new = $new;
    }

    public function __construct($allowed = null, array $old = null, array $new = null){
        $this->allowed = isset($allowed) ? (boolean) $allowed : null;
        $this->old = $old;
        $this->new = $new;
    }

    public function hasCriteria(){
        if (isset($this->new) || isset($this->old)){
            return true;
        }
        return false;
    }
}

