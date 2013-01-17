<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer\Reference;

use Doctrine\ODM\MongoDB\DocumentManager;
use Sds\DoctrineExtensions\Serializer\Serializer;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Eager implements ReferenceSerializerInterface {

    public static function serialize($id, array $mapping, DocumentManager $documentManager){

        return Serializer::toArray(
            $documentManager->getRepository($mapping['targetDocument'])->findOneBy(['id' => $id]),
            $documentManager
        );
    }
}
