<?php

namespace Madmatt\ActivityLog\Model;

use Madmatt\ActivityLog\FieldType\DBTarget;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\FieldType\DBPolymorphicForeignKey;
use SilverStripe\Security\Member;

/**
 * Class ActivityLogEntry
 * @package Madmatt\ActivityLog
 *
 * Represents a single activity log entry. Each entry can be seen by one {@link Member}.
 *
 * Each entry has the following fields that are used:
 * @property DBDatetime $Created The time the activity occurred
 * @property string $ClassName: The type (class name) of the activity that occurred
 * @property Member $Actor: The member who performed the action that resulted in this entry being created
 * @property Member $Owner: The member that should see this activity
 * @property string $TargetClass: The class name of the object that the action was performed on
 * @property int $TargetID: The unique ID of the object that the action was performed on
 *
 * The 'Target' field works as a foreign key to any {@link DataObject}. For example, if an activity is tracking who
 * published a page, then we would have:
 * - TargetClass: SilverStripe\CMS\Model\SiteTree
 * - TargetID: 1
 *             For example, if this is a Page, then `TargetClass` = 'Page', `TargetID` = 123.
 */
class ActivityLogEntry extends DataObject
{
    private static $db = [
        'Target' => DBPolymorphicForeignKey::class
    ];

    private static $has_one = [
        'Actor' => Member::class,
        'Owner' => Member::class
    ];

    private static $table_name = 'ActivityLogEntry';

    private static $default_sort = 'Created DESC';
}