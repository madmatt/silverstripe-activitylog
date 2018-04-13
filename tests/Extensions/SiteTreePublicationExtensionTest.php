<?php

namespace Madmatt\ActivityLog\Tests\Extensions;

use Madmatt\ActivityLog\Extensions\SiteTreePublicationExtension;
use Madmatt\ActivityLog\Model\ActivityLogEntry;
use Madmatt\ActivityLog\Services\ActivityLogService;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

class SiteTreePublicationExtensionTest extends SapphireTest
{
    protected static $required_extensions = [
        SiteTree::class => [ SiteTreePublicationExtension::class ]
    ];

    protected static $fixture_file = 'SiteTreePublicationExtensionTest.yml';

    public function testOnAfterPublish()
    {
        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'jane');
        $member->write();
        Security::setCurrentUser($member);

        $st = new SiteTree();
        $st->write();
        $st->publishRecursive();

        // Extension is not enabled by default, so should not have written an activity log entry
        $this->assertSame(0, ActivityLogEntry::get()->count());

        // Enabling the extension should write a log entry
        Config::modify()->set(ActivityLogService::class, 'enabled_activities', [ SiteTreePublicationExtension::class ]);

        $st = new SiteTree();
        $st->write();
        $st->publishRecursive();
        $entry = ActivityLogEntry::get()->first();
        $this->assertSame(1, ActivityLogEntry::get()->count());
        $this->assertSame($member->ID, $entry->Owner()->ID);
        $this->assertSame($member->ID, $entry->Actor()->ID);
        $this->assertSame($st->ID, $entry->TargetID);
    }
}
