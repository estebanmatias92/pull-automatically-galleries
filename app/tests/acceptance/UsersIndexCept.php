<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that users index page works');
$I->amOnPage('/users');
$I->see('Users:');