<?php

namespace Madmatt\ActivityLog\Extensions;

use Madmatt\ActivityLog\Model\Activities\SiteTreePublicationActivity;
use Madmatt\ActivityLog\Model\ActivityLogEntry;
use Madmatt\ActivityLog\Services\ActivityLogService;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Security;

/**
 * Class SiteTreePublicationExtension
 * @package Madmatt\ActivityLog
 *
 * An example extension that creates an {@link ActivityLogEntry} every time a page is published.
 *
 * This activity log entry is visible only to the person who published the object.
 */
class SiteTreePublicationExtension extends DataExtension
{
    /**
     * @var ActivityLogService
     */
    public $service;

    private static $dependencies = [
        'service' => '%$' . ActivityLogService::class
    ];

    /**
     * @param SiteTree &$original The current Live SiteTree record prior to publish
     */
    public function onAfterPublish(?SiteTree &$original)
    {
        if ($this->service->isActivityEnabled(self::class)) {
            $member = Security::getCurrentUser();
            $entry = new SiteTreePublicationActivity();

            $entry->TargetClass = $this->owner->class;
            $entry->TargetID = $this->owner->ID;
            $entry->ActorID = $member->ID;
            $entry->OwnerID = $member->ID;

            $entry->write();
        }
    }
}
