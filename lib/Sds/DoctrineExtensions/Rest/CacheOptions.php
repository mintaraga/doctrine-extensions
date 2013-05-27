<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Rest;

use Zend\Stdlib\AbstractOptions;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class CacheOptions extends AbstractOptions
{
    protected $public;

    protected $private;

    protected $noCache;

    protected $maxAge;

    public function getPublic() {
        return $this->public;
    }

    public function setPublic($public) {
        $this->public = (bool) $public;
    }

    public function getPrivate() {
        return $this->private;
    }

    public function setPrivate($private) {
        $this->private = (bool) $private;
    }

    public function getNoCache() {
        return $this->noCache;
    }

    public function setNoCache($noCache) {
        $this->noCache = (bool) $noCache;
    }

    public function getMaxAge() {
        return $this->maxAge;
    }

    public function setMaxAge($maxAge) {
        $this->maxAge = (integer) $maxAge;
    }
}
