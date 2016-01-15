<?php

namespace App\Api\GitLab;

use GuzzleHttp;

/**
 * Used to authenticate with the GitLab instance and get GitLab
 * configuration values.
 * Want to look into integrating this with Lumen's auth drivers.
 */
class GitLab
{
    /**
     * Path to use for the API
     * @var string
     */
    protected $apiPath = '/api/v3/';

    /**
     * Username used to authenticate with GitLab
     * @var string
     */
    protected $username;

    /**
     * Password used to authenticate with GitLab
     * @var string
     */
    protected $password;

    /**
     * Host for the GitLab
     * @var string
     */
    protected $host;

    /**
     * User object returned by GitLab
     * @var stdClass
     */
    protected $user;

    /**
     * Creates a GitLab Auth instance
     * @param string $username Username to authenticate with GitLab
     * @param string $password Password to authenticate with GitLab
     * @param string $host     Host for the GitLab to authenticate with
     */
    public function __construct(
        $username = '',
        $password = '',
        $host = ''
    ) {
        if (empty($username)) {
            $username = env('GITLAB_AUTH_USER');
        }

        if (empty($password)) {
            $password = env('GITLAB_AUTH_PASS');
        }

        if (empty($host)) {
            $host = env('GITLAB_URL');
        }

        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
    }

    /**
     * Authenticates the given username and password with GitLab
     * @return self
     */
    public function authenticate()
    {
        $response = $this->sendApiRequest('POST', 'session', [
            'login'    => $this->username,
            'password' => $this->password,
        ], false);

        $this->user = json_decode($response->getBody()->getContents());

        return $this;
    }

    /**
     * Sends an API request to GitLab
     * @param  string $httpAction   HTTP action GET|POST|PUT..etc
     * @param  string $path         Path excluding the api/$version
     * @param  array  $formParams   Array of any form params to send.
     * @param  string $privateToken Private token for auth. Pass in false for no token.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendApiRequest($httpAction, $path, $formParams = [], $privateToken = '')
    {
        $client   = new GuzzleHttp\Client();
        $url = $this->host . '/' . $this->apiPath . $path;

        $options = [];

        if ([] != $formParams) {
            $options['form_params'] = $formParams;
        }

        if (false !== $privateToken) {
            if ('' === $privateToken) {
                $privateToken = $this->getPrivateToken();
            }

            $options['headers'] = [
                'PRIVATE-TOKEN' => $privateToken
            ];
        }

        $response = $client->request($httpAction, $url, $options);

        return $response;
    }

    /**
     * Gets the private token to use for talking to GitLabs API.
     * @return null|string
     */
    public function getPrivateToken()
    {
        if (null === $this->user) {
            $this->authenticate();
        }

        return $this->user->private_token;
    }

    /**
     * Get the host for the GitLab
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get the user object returned by GitLab
     * @return stdClass
     */
    public function getUser()
    {
        return $this->user;
    }
}