<?php

namespace Madmatt\ActivityLog\Tests\Services;

use Madmatt\ActivityLog\Services\ActivityLogService;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class ActivityLogServiceTest extends SapphireTest
{
    protected static $fixture_file = 'ActivityLogServiceTest.yml';

    public function testGetActivitesFor()
    {
        /** @var ActivityLogService $service */
        $service = Injector::inst()->get(ActivityLogService::class);
        
        /** @var Member $jane */
        $jane = $this->objFromFixture(Member::class, 'jane');
        
        /** @var Member $lisa */
        $lisa = $this->objFromFixture(Member::class, 'lisa');

        // null returned if no member is given
        $this->assertNull($service->getActivitiesFor());
        $this->assertNull($service->getActivitiesFor(null));
        
        // Lisa owns one activity, Jane owns none
        $this->assertSame(1, $service->getActivitiesFor($lisa)->count());
        $this->assertSame(0, $service->getActivitiesFor($jane)->count());
    }
}