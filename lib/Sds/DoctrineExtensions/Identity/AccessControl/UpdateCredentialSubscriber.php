<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity\AccessControl;

use Sds\DoctrineExtensions\Identity\Actions;
use Sds\DoctrineExtensions\Identity\Events;
use Sds\DoctrineExtensions\Identity\UpdateCredentialEventArgs;
use Sds\DoctrineExtensions\AccessControl\AbstractAccessControlSubscriber;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UpdateCredentialSubscriber extends AbstractAccessControlSubscriber
{

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return [
            Events::preUpdateCredential
        ];
    }

    public function preUpdateCredential(UpdateCredentialEventArgs $eventArgs)
    {
        if (! ($accessController = $this->getAccessController())){
            //Access control is not enabled
            return;
        }

        $document = $eventArgs->getDocument();

        if ( ! $accessController->isAllowed(Actions::updateCredential, null, $document)->getIsAllowed()) {

            $document->setCredential($eventArgs->getOldCredential());

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(Events::updateCredentialDenied)) {
                $eventManager->dispatchEvent(
                    Events::updateCredentialDenied,
                    $eventArgs
                );
            }
        }
    }
}
