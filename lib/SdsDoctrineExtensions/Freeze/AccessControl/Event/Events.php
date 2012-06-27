<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze\AccessControl\Event;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
final class Events
{
    private function __construct() {}

    /**
     * Triggered when activeUser attempts to freeze a document they don't have permission
     * for
     */
    const freezeDenied = 'freezeDenied';

    /**
     * Triggers when activeUser attempts to thaw a document they don't have permission
     * for
     */
    const thawDenied = 'thawDenied';
}