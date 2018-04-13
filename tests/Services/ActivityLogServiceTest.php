<?php

namespace Madmatt\ActivityLog\Tests\Services;

use Madmatt\ActivityLog\Model\Activities\SiteTreePublicationActivity;
use Madmatt\ActivityLog\Services\ActivityLogService;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class ActivityLogServiceTest extends SapphireTest
{
    protected static $fixture_file = 'ActivityLogServiceTest.yml';

    /**
     * @var ActivityLogService
     */
    private $service;

    public function setUp()
    {
        parent::setUp();
        $this->service = Injector::inst()->get(ActivityLogService::class);
    }

    public function testGetActivitesFor()
    {
        /** @var Member $jane */
        $jane = $this->objFromFixture(Member::class, 'jane');
        
        /** @var Member $lisa */
        $lisa = $this->objFromFixture(Member::class, 'lisa');

        // null returned if no member is given
        $this->assertNull($this->service->getActivitiesFor());
        $this->assertNull($this->service->getActivitiesFor(null));
        
        // Lisa owns one activity, Jane owns none
        $this->assertSame(1, $this->service->getActivitiesFor($lisa)->count());
        $this->assertSame(0, $this->service->getActivitiesFor($jane)->count());
    }

    public function testIsActivityEnabled()
    {
        $this->assertFalse($this->service->isActivityEnabled(SiteTreePublicationActivity::class));

        Config::modify()->set(ActivityLogService::class, 'enabled_activities', [ SiteTreePublicationActivity::class ]);
        $this->assertTrue($this->service->isActivityEnabled(SiteTreePublicationActivity::class));
    }
}