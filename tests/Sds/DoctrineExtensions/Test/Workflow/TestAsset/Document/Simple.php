<?php

namespace Sds\DoctrineExtensions\Test\Workflow\TestAsset\Document;

use Sds\Common\Workflow\WorkflowAwareInterface;
use Sds\DoctrineExtensions\Workflow\Behaviour\WorkflowAwareTrait;

//Annotaion imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/** @ODM\Document */
class Simple implements WorkflowAwareInterface {

    use WorkflowAwareTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}