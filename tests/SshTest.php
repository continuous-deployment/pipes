<?php

use Ssh\Authentication\Password;
use Ssh\Configuration;
use Ssh\Session;

class SshTest extends TestCase
{
    /**
     * A basic test of ssh.
     *
     * @return void
     */
    public function testSsh()
    {
        $configuration = new Configuration(env('SSH_HOST'));
        $session = new Session($configuration);
        $authentication = new Password(env('SSH_USER'), env('SSH_PASSWORD'));
        $login = $authentication->authenticate($session->getResource());
        $exec = $session->getExec();
        $this->assertTrue($login, 'Authentification failed.');
    }
}
