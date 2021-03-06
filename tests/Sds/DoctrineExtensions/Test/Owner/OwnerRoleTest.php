<?php

namespace Sds\DoctrineExtensions\Test\Owner;

use Sds\DoctrineExtensions\AccessControl\Events as Events;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\Owner\TestAsset\Document\OwnerDoc;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

class OwnerRoleTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.accessControl' => true,
                'extension.owner' => true
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                    'identity' => function(){
                        $identity = new RoleAwareIdentity();
                        $identity->setIdentityName('toby');
                        return $identity;
                    }
                ]
            ]
        ]);

        $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
    }

    public function testOwnerAllow(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::updateDenied, $this);

        $testDoc = new OwnerDoc();
        $testDoc->setName('my test');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $this->assertEquals('my test', $testDoc->getName());
        $id = $testDoc->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->setName('different name');
        $documentManager->flush();

        $this->assertEquals('different name', $testDoc->getName());
        $this->assertFalse(isset($this->calls[Events::updateDenied]));
    }

    public function testOwnerDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(Events::updateDenied, $this);

        $testDoc = new OwnerDoc();
        $testDoc->setName('my test');
        $testDoc->setOwner('bobby');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $this->assertEquals('my test', $testDoc->getName());
        $id = $testDoc->getId();

        $documentManager->clear();
        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $testDoc->setName('different name');
        $documentManager->flush();

        $this->assertEquals('my test', $testDoc->getName());
        $this->assertTrue(isset($this->calls[Events::updateDenied]));
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}