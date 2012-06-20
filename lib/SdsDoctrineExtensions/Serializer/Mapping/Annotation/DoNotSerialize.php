<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Serializer\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Mark a field to be skipped during serialization
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class DoNotSerialize extends Annotation
{
}