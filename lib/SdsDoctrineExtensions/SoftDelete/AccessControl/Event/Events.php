<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\AccessControl\Event;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{
    private function __construct() {}

    /**
     * Triggered when activeUser attempts to soft delete a document they don't have permission
     * for
     */
    const softDeleteDenied = 'softDeleteDenied';

    /**
     * Triggers when activeUser attempts to restore a document they don't have permission
     * for
     */
    const restoreDenied = 'restoreDenied';
}