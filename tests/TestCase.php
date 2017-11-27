<?php

namespace Tests;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');

        Passport::actingAs($user);

        return $this;
    }

    protected function tearDown()
    {
        $refl = new \ReflectionObject($this);

        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }

        parent::tearDown();
    }
}
