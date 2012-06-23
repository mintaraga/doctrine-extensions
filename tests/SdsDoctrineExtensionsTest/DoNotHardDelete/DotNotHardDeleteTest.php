<?php

namespace SdsDoctrineExtensionsTest\DoNotHardDelete;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensionsTest\DoNotHardDelete\TestAsset\Document\Deleteable;
use SdsDoctrineExtensionsTest\DoNotHardDelete\TestAsset\Document\NotDeleteable;
use SdsDoctrineExtensionsTest\DoNotHardDelete\TestAsset\Subscriber;

class DoNotHardDeleteTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\DoNotHardDelete' => null));

        $this->configure(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\DoNotHardDelete\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testAllowDelete(){

        $documentManager = $this->documentManager;

        $testDoc = new Deleteable();
        $testDoc->setName('go');
        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('go', $testDoc->getName());

        $documentManager->remove($testDoc);
        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNull($testDoc);
    }

    public function testDenyDelete(){

        $documentManager = $this->documentManager;

        $testDoc = new NotDeleteable();
        $testDoc->setName('stay');
        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('stay', $testDoc->getName());

        $documentManager->remove($testDoc);
        $documentManager->flush();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertNotNull($testDoc);
        $this->assertEquals('stay', $testDoc->getName());
    }

    public function testEventCalled() {

        $subscriber = new Subscriber();

        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();
        $eventManager->addEventSubscriber($subscriber);

        $testDoc = new NotDeleteable();
        $testDoc->setName('stay');
        $id = $this->persist($testDoc);

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $documentManager->remove($testDoc);

        $this->assertFalse($subscriber->getHardDeleteRefusedCalled());
        $documentManager->flush();
        $this->assertTrue($subscriber->getHardDeleteRefusedCalled());
    }
}