<?php

class SigninCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToLogin(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('email', 'ninon.nurulfaiza@gmail.com');
        $I->fillField('password', 'ninon123');
        $I->click('Log in');
        $I->see("ninon nurul faiza");
    }
}
