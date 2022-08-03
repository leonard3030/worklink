<?php
namespace system\library;

class FacebookApi
{

    public $options = [];

    public function __construct($options)
    {
        $this->options = $options;
    }
    /**
     * Make call to facebook endpoint
     *
     * @param string $endpoint make call to this enpoint
     * @param array $params array keys are the variable names required by the endpoint
     *
     * @return array $response
     */
    public function makeFacebookApiCall($endpoint, $params)
    {
        // open curl call, set endpoint and other curl params
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint . '?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // get curl response, json decode it, and close curl
        $fbResponse = curl_exec($ch);
        $fbResponse = json_decode($fbResponse, true);
        curl_close($ch);

        return array( // return response data
            'endpoint' => $endpoint,
            'params' => $params,
            'has_errors' => isset($fbResponse['error']) ? true : false, // boolean for if an error occured
            'error_message' => isset($fbResponse['error']) ? $fbResponse['error']['message'] : '', // error message
            'fb_response' => $fbResponse, // actual response from the call
        );
    }

    // Set redict url
    public function setRedirectUrl($url)
    {
        $this->options['redirect_url'] = $url;
    }
    /**
     * Get facebook api login url that will take the user to facebook and present them with login dialog
     *
     * Endpoint: https://www.facebook.com/{fb-graph-api-version}/dialog/oauth?client_id={app-id}&redirect_uri={redirect-uri}&state={state}&scope={scope}&auth_type={auth-type}
     *
     * @param void
     *
     * @return string
     */

    public function getFacebookLoginUrl()
    {
        // endpoint for facebook login dialog
        $endpoint = 'https://www.facebook.com/' . $this->options['graph_version'] . '/dialog/oauth';

        $params = array( // login url params required to direct user to facebook and promt them with a login dialog
            'client_id' => $this->options['app_id'],
            'redirect_uri' => $this->options['redirect_url'],
            'state' => 'eciphp',
            'scope' => 'email',
            'auth_type' => 'rerequest',
        );

        // return login url
        return $endpoint . '?' . http_build_query($params);
    }

    /**
     * Get an access token with the code from facebook
     *
     * Endpoint https://graph.facebook.com/{fb-graph-version}/oauth/access_token?client_id{app-id}&client_secret={app-secret}&redirect_uri={redirect_uri}&code={code}
     *
     * @param string $code
     *
     * @return array $response
     */
    public function getAccessTokenWithCode($code)
    {
        // endpoint for getting an access token with code
        $endpoint = 'https://graph.facebook.com/' . $this->options['graph_version'] . '/oauth/access_token';
        $params = array( // params for the endpoint
            'client_id' => $this->options['app_id'],
            'client_secret' => $this->options['app_secret'],
            'redirect_uri' => $this->options['redirect_url'],
            'code' => $code,
        );
        // make the api call
        return json_decode(json_encode($this->makeFacebookApiCall($endpoint, $params)));
    }

    /**
     * Get a users facebook info
     *
     * Endpoint https://graph.facebook.com/me?fields={fields}&access_token={access-token}
     *
     * @param string $accessToken
     *
     * @return array $response
     */
    public function getFacebookUserInfo($accessToken)
    {
        // endpoint for getting a users facebook info
        $endpoint = 'https://graph.facebook.com/' . 'me';

        $params = array( // params for the endpoint
            'fields' => 'first_name,last_name,email,picture',
            'access_token' => $accessToken,
        );

        // make the api call
        return json_decode(json_encode($this->makeFacebookApiCall($endpoint, $params)));
    }

    /**
     * Get debug info on an access token
     *
     * Endpoint https://graph.facebook.com/debug_token?input_token={access-token}&access_token={access-token}
     *
     * @param string $accessToken
     *
     * @return array $response
     */
    public function getDebugAccessTokenInfo($accessToken)
    {
        // endpoint for getting debug info
        $endpoint = 'https://graph.facebook.com/' . 'debug_token';

        $params = array( // params for the endpoint
            'input_token' => $accessToken,
            'access_token' => $accessToken,
        );

        // make the api call
        return json_decode(json_encode($this->makeFacebookApiCall($endpoint, $params)));
    }
}
