<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Annotation\EventType;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber, AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;

    /**
     *
     * @var \Sds\Common\Validator\ValidatorInterface
     */
    protected $documentValidator;

    protected $validateOnFlush;

    /**
     *
     * @return \Sds\Common\Validator\ValidatorInterface
     */
    public function getDocumentValidator() {
        return $this->documentValidator;
    }

    /**
     *
     * @param \Sds\Common\Validator\ValidatorInterface $validator
     */
    public function setDocumentValidator(DocumentValidatorInterface $documentValidator) {
        $this->documentValidator = $documentValidator;
    }

    public function getValidateOnFlush() {
        return $this->validateOnFlush;
    }

    public function setValidateOnFlush($validateOnFlush) {
        $this->validateOnFlush = (boolean) $validateOnFlush;
    }

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        $events = array(
            Sds\ValidatorGroup::event
        );
        if ($this->getValidateOnFlush()) {
            $events[] = ODMEvents::onFlush;
        }
        return $events;
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param \Sds\Common\Validator\ValidatorInterface $validator
     */
    public function __construct(
        Reader $annotationReader,
        DocumentValidatorInterface $documentValidator,
        $validateOnFlush
    ){
        $this->setAnnotationReader($annotationReader);
        $this->setDocumentValidator($documentValidator);
        $this->setValidateOnFlush($validateOnFlush);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationValidatorGroup(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        $validatorMetadata = [];

        if (is_array($annotation->value)){
            foreach ($annotation->value as $subAnnotation){
                $validatorMetadata = $this->processAnnotation($subAnnotation, $validatorMetadata);
            }
        } else {
            $validatorMetadata = $this->processAnnotation($annotation->value, $validatorMetadata);
        }

        switch ($eventArgs->getEventType()){
            case 'document':
                $eventArgs->getMetadata()->validator = $validatorMetadata;
                break;
            case 'property':
                $eventArgs->getMetadata()->validator['fields'][$eventArgs->getReflection()->getName()] = $validatorMetadata;
                break;
        }
    }

    protected function processAnnotation($annotation, $validatorMetadata){

        switch (true){
            case ($annotation instanceof Sds\Validator):
                $validatorMetadata['validatorGroup'][$annotation->class] = $annotation->options;
                break;
            case ($annotation instanceof Sds\Required):
                if ($annotation->value){
                    $validatorMetadata['validatorGroup']['Sds\Common\Validator\RequiredValidator'] = null;
                } else {
                    $validatorMetadata['validatorGroup']['Sds\Common\Validator\NotRequiredValidator'] = null;
                }
                break;
            default:

        }

        return $validatorMetadata;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {
            $metadata = $documentManager->getClassMetadata(get_class($document));

            if (!$this->documentValidator->isValid($document, $metadata)) {

                // Updates to invalid documents are not allowed. Roll them back
                $unitOfWork->clearDocumentChangeSet(spl_object_hash($document));

                $eventManager = $documentManager->getEventManager();

                // Raise invalidUpdate
                if ($eventManager->hasListeners(Events::invalidUpdate)) {
                    $eventManager->dispatchEvent(
                        Events::invalidUpdate,
                        new EventArgs($document, $documentManager, $this->documentValidator->getMessages())
                    );
                }
            }
        }


        foreach ($unitOfWork->getScheduledDocumentInsertions() as $document) {
            $metadata = $documentManager->getClassMetadata(get_class($document));

            if (!$this->documentValidator->isValid($document, $metadata)) {

                //stop creation
                $unitOfWork->detach($document);

                $eventManager = $documentManager->getEventManager();

                // Raise invalidUpdate
                if ($eventManager->hasListeners(Events::invalidCreate)) {
                    $eventManager->dispatchEvent(
                        Events::invalidCreate,
                        new EventArgs($document, $documentManager, $this->documentValidator->getMessages())
                    );
                }
            }
        }
    }
}
