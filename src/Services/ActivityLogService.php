<?php

namespace Madmatt\ActivityLog\Services;

use Madmatt\ActivityLog\Model\ActivityLogEntry;
use SilverStripe\ORM\DataList;
use SilverStripe\Security\Member;

class ActivityLogService
{
    public function getActivitiesFor(Member $member = null): ?DataList
    {
        if (!$member) {
            return null;
        }

        return ActivityLogEntry::get()->filter('OwnerID', $member->ID);
    }
}