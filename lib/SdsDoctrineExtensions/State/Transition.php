<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\State;

/**
 * Implementation of SdsCommon\Workflow\TransitionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Transition
{
    /**
     * Return the action name for this transition.
     * Used for access control
     *
     * @return string
     */
    static public function getAction($fromState, $toState) {
        return (string) $fromState . '-' . (string) $toState;
    }
}
