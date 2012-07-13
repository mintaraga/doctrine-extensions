<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\EventArgs as BaseEventArgs;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * Arguments for annotation events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationEventArgs extends BaseEventArgs {

    protected $metadata;

    protected $eventType;

    protected $annotation;

    protected $reflection;

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo $metadata
     * @param string $eventType
     * @param \Doctrine\Common\Annotations\Annotation $annotation
     * @param mixed $reflection
     */
    public function __construct(
        ClassMetadataInfo $metadata,
        $eventType,
        Annotation $annotation,
        $reflection
    ) {
        $this->metadata = $metadata;
        $this->eventType = $eventType;
        $this->annotation = $annotation;
        $this->reflection = $reflection;
    }

    public function getMetadata() {
        return $this->metadata;
    }

    public function getEventType() {
        return $this->eventType;
    }

    public function getAnnotation() {
        return $this->annotation;
    }

    public function getReflection() {
        return $this->reflection;
    }
}