<?php

class GroupTest extends TestCase {
    
	public function testAccessGroupNotLogged()
	{
        $this->app->singleton(
                'App\Services\Contracts\ICurrentUser',
                'Mock\MockNoUser'
            );

		$response = $this->call('GET', 'group');
        //redirect
		$this->assertEquals(302, $response->getStatusCode());        
        $this->visit('group')
                ->seePageis('login');
	}
    
    public function testAccessGroupLogged()
	{
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$response = $this->call('GET', '/group');
        //ok
		$this->assertEquals(200, $response->getStatusCode());
	}
    
    public function testCreateGroup()
	{
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(3);
        
		$srv = $this->app->make('App\Services\GroupService');
        $start = new DateTime();
        $start->add(DateInterval::createFromDateString('1 month'));
        $end = new DateTime();
        $end->add(DateInterval::createFromDateString('6 months'));
        $ret = $srv->CreateGroup('group test', 'plop plop plop',
                $start, $end);
        $this->assertGreaterThan(0, $ret);
        
        $users = $srv->GetUsers($ret);
        
        $notifs = $srv->GetNotifications($ret);

        //notification/history entry
        $this->assertEquals($notifs->count(), 1);
        $this->assertEquals($notifs->first()->type, App\Models\Types\NotificationTypes::JOIN);
        $this->assertEquals($notifs->first()->id_user, 3);
        $this->assertEquals($notifs->first()->id_group, $ret);
        
        //users in group, me
        $this->assertEquals($users->count(), 1);
        $this->assertEquals($users->first(), 3);
	}

    /**
    * @expectedException \App\Exceptions\InvalidOperationException
    * @expectedExceptionMessage Invalid operation: group name is not unique
    */
    public function testFailCreateGroup()
	{
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$srv = $this->app->make('App\Services\GroupService');
        $start = new DateTime();
        $start->add(DateInterval::createFromDateString('1 month'));
        $end = new DateTime();
        $end->add(DateInterval::createFromDateString('6 months'));
        $ret = $srv->CreateGroup('group test', 'plop plop plop', 
                $start, $end);
        $ret2 = $srv->CreateGroup('group test', 'plop plop plop plop',
                $start, $end);
	}
    
    public function testCreateGroupInviteAndJoin()
	{
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$srv = $this->app->make('App\Services\GroupService');
        $pollSrv = $this->app->make('App\Services\PollService');
        $start = new DateTime();
        $start->add(DateInterval::createFromDateString('1 month'));
        $end = new DateTime();
        $end->add(DateInterval::createFromDateString('6 months'));
        $grp = $srv->CreateGroup('group test', 'plop plop plop',
                $start, $end);
        $poll = $srv->RecommandForGroup(2, $grp, 'cool guy boys! (testCreateGroupInviteAndJoin)');
        
        $invits = $srv->GetApplications($grp);
        $this->assertEquals($invits->count(), 1);
        $this->assertEquals($invits->first()->id_user, 2);
        $this->assertEquals($invits->first()->id_game, 0);
        
        $notifs = $srv->GetNotifications($grp);
        $this->assertEquals($notifs->count(), 2);
        $this->assertEquals($notifs->last()->id_user, 2);
        $this->assertEquals($notifs->last()->id_poll, $poll);
        $this->assertEquals($notifs->last()->type, App\Models\Types\NotificationTypes::PROPOSE);
        
        //1 guy in group, 1 asking, 1 poll, 1 vote needed then
        $pollSrv->AddVote($poll, App\Models\Types\VoteTypes::YES);
        
        //should be closed automatically, usersCount == votesCount so message to queue
        //see first line for the test
        
        //this is what will happen then
        $job = new \App\Jobs\ClosePoll($poll);
        $job->handle($pollSrv, $srv);
        
        //And the result
        $users = $srv->GetUsers($grp);
        $this->assertEquals($users->count(), 2);
	}
    
    public function testCreateGroupApplyAndJoin()
	{
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$srv = $this->app->make('App\Services\GroupService');
        $pollSrv = $this->app->make('App\Services\PollService');
        $start = new DateTime();
        $start->add(DateInterval::createFromDateString('1 month'));
        $end = new DateTime();
        $end->add(DateInterval::createFromDateString('6 months'));
        $grp = $srv->CreateGroup('group test', 'plop plop plop',
                $start, $end);
        
        $user->LogUser(2);
        $poll = $srv->ApplyForGroup($grp, 'cool guy boys! (testCreateGroupApplyAndJoin)');
        
        //back to user in group else we don't get shit
        $user->LogUser(1);
        $invits = $srv->GetApplications($grp);
        $this->assertEquals($invits->count(), 1);
        $this->assertEquals($invits->first()->id_user, 2);
        $this->assertEquals($invits->first()->id_game, 0);
        
        $notifs = $srv->GetNotifications($grp);
        $this->assertEquals($notifs->count(), 2);
        $this->assertEquals($notifs->last()->id_user, 2);
        $this->assertEquals($notifs->last()->id_poll, $poll);
        $this->assertEquals($notifs->last()->type, App\Models\Types\NotificationTypes::APPLY);
        
        //1 guy in group, 1 asking, 1 poll, 1 vote needed then
        $pollSrv->AddVote($poll, App\Models\Types\VoteTypes::YES);
        
        //should be closed automatically, usersCount == votesCount so message to queue
        //this is what will happen then
        $job = new \App\Jobs\ClosePoll($poll);
        $job->handle($pollSrv, $srv);
        
        $invits = $srv->GetApplications($grp);
        $this->assertEquals($invits->count(), 0);
        
        $notifs = $srv->GetNotifications($grp);
        $this->assertEquals($notifs->count(), 3);
        
        //And the result
        $users = $srv->GetUsers($grp);
        $this->assertEquals($users->count(), 2);
	}
    
    /**
    * @expectedException \App\Exceptions\InvalidOperationException
    * @expectedExceptionMessage Invalid operation: Cannot recommand to this group, already in.
    */
    public function testCreateGroupInviteAndAlreadyIn()
	{        
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$srv = $this->app->make('App\Services\GroupService');
        $pollSrv = $this->app->make('App\Services\PollService');
        $start = new DateTime();
        $start->add(DateInterval::createFromDateString('1 month'));
        $end = new DateTime();
        $end->add(DateInterval::createFromDateString('6 months'));
        $grp = $srv->CreateGroup('group test', 'plop plop plop',
                $start, $end);
        $this->assertTrue($srv->IsInGroup(1, $grp));
        $poll = $srv->RecommandForGroup(1, $grp, 'cool guy boys (testCreateGroupInviteAndAlreadyIn)!');
	}
    
    public function testCreateGroupApplyAndRefuse()
	{
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$srv = $this->app->make('App\Services\GroupService');
        $pollSrv = $this->app->make('App\Services\PollService');
        $start = new DateTime();
        $start->add(DateInterval::createFromDateString('1 month'));
        $end = new DateTime();
        $end->add(DateInterval::createFromDateString('6 months'));
        $grp = $srv->CreateGroup('group test', 'plop plop plop',
                $start, $end);
        $user->LogUser(2);
        $poll = $srv->ApplyForGroup($grp, 'cool guy boys (testCreateGroupApplyAndRefuse)!');
        
        //back to user in group else we don't get shit
        $user->LogUser(1);
        $invits = $srv->GetApplications($grp);
        $this->assertEquals($invits->count(), 1);
        $this->assertEquals($invits->first()->id_user, 2);
        $this->assertEquals($invits->first()->id_game, 0);
        
        $notifs = $srv->GetNotifications($grp);
        $this->assertEquals($notifs->count(), 2);
        $this->assertEquals($notifs->last()->id_user, 2);
        $this->assertEquals($notifs->last()->id_poll, $poll);
        $this->assertEquals($notifs->last()->type, App\Models\Types\NotificationTypes::APPLY);

        //1 guy in group, 1 asking, 1 poll, 1 vote needed then
        $pollSrv->AddVote($poll, App\Models\Types\VoteTypes::NO);
        
        //should be closed automatically, usersCount == votesCount so message to queue
        //this is what will happen then
        $job = new \App\Jobs\ClosePoll($poll);
        $job->handle($pollSrv, $srv);
        
        $invits = $srv->GetApplications($grp);
        $this->assertEquals($invits->count(), 0);
        
        $notifs = $srv->GetNotifications($grp);
        $this->assertEquals($notifs->count(), 3);
        
        //Still alone
        $users = $srv->GetUsers($grp);
        $this->assertEquals($users->count(), 1);
	}
    
    public function testCreateGroupInviteAndDeleteApplication()
	{
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$srv = $this->app->make('App\Services\GroupService');
        $pollSrv = $this->app->make('App\Services\PollService');
        $start = new DateTime();
        $start->add(DateInterval::createFromDateString('1 month'));
        $end = new DateTime();
        $end->add(DateInterval::createFromDateString('6 months'));
        $grp = $srv->CreateGroup('group test', 'plop plop plop',
                $start, $end);
        $this->assertFalse ($srv->IsInGroup(2, $grp));
        $poll = $srv->RecommandForGroup(2, $grp, 'cool guy boys! (testCreateGroupInviteAndDeleteApplication)');
        
        $user->LogUser(2);
        $srv->DeleteApplication($grp, 2);
        
        //back to user in group else we don't get shit
        $user->LogUser(1);
        $invits = $srv->GetApplications($grp);
        $this->assertEquals($invits->count(), 0);
        
        $notifs = $srv->GetNotifications($grp);
        $this->assertEquals($notifs->count(), 3);
        $this->assertEquals($notifs->last()->id_user, 2);
        $this->assertEquals($notifs->last()->id_poll, null); //no poll
        $this->assertEquals($notifs->last()->type, App\Models\Types\NotificationTypes::DELETE_APPLY);
        
        //still alone
        $users = $srv->GetUsers($grp);
        $this->assertEquals($users->count(), 1);
	}
    
    /**
    * @expectedException \App\Exceptions\InvalidOperationException
    * @expectedExceptionMessage Invalid operation: A vote exists already
    */
    public function testCreateGroupInviteAndVoteTwice()
	{   
        $user = $this->app->make('App\Services\Contracts\ICurrentUser');
        $user->LogUser(1);
        
		$srv = $this->app->make('App\Services\GroupService');
        $pollSrv = $this->app->make('App\Services\PollService');
        $start = new DateTime();
        $start->add(DateInterval::createFromDateString('1 month'));
        $end = new DateTime();
        $end->add(DateInterval::createFromDateString('6 months'));
        $grp = $srv->CreateGroup('group test', 'plop plop plop',
                $start, $end);
        $poll = $srv->RecommandForGroup(2, $grp, 'cool guy boys!');
        $pollSrv->AddVote($poll, App\Models\Types\VoteTypes::YES);
        $pollSrv->AddVote($poll, App\Models\Types\VoteTypes::NO);
	}
}
