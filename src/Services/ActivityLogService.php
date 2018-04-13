<?php

namespace Madmatt\ActivityLog\Services;

use Madmatt\ActivityLog\Model\ActivityLogEntry;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\ORM\DataList;
use SilverStripe\Security\Member;

/**
 * Class ActivityLogService
 * @package Madmatt\ActivityLog
 *
 * Service that helps tie all activity log functionality together.
 *
 * By default, no activity log classes are enabled, you must manually enable the loggers that you wish to use, or create
 * your own. Example loggers can be found under the Madmatt\ActivityLog\Model\Activities namespace.
 */
class ActivityLogService
{
    use Configurable;
    
    /**
     * List of all enabled activities, set by config.
     * 
     * @var array
     * @config
     */
    private static $enabled_activities = [];
    
    public function getActivitiesFor(Member $member = null): ?DataList
    {
        if (!$member) {
            return null;
        }

        return ActivityLogEntry::get()->filter('OwnerID', $member->ID);
    }

    /**
     * Determines whether the given activity class is enabled or not. Used primarily by activities themselves when 
     * determining if an activity should be logged or not.
     * 
     * @param string $classNameThe fully-qualified classname of the activity class
     * @return bool
     */
    public function isActivityEnabled($className)
    {
        return in_array($className, $this->config()->enabled_activities);
    }
}