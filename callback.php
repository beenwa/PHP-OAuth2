<?php
session_start();

require('Client.php');
require('GrantType/IGrantType.php');
require('GrantType/AuthorizationCode.php');

const CLIENT_ID     = 'MY_CLIENT_ID';
const CLIENT_SECRET = 'MY_CLIENT_SECRET';

const REDIRECT_URI           = 'http://www.example.com/callback.php';
const AUTHORIZATION_ENDPOINT = 'http://www.example.com/authorize.php';
const TOKEN_ENDPOINT         = 'http://www.example.com/token.php';

$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);
if (!isset($_GET['code']))
{
  $auth_url = $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI);
  header('Location: ' . $auth_url);
  die('Redirect');
}
else
{
  $params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
    
	if( $response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params) )
	{
		if( $response['code'] == 200 && !empty($response['result']) )
		{	
			$info = $response['result'];
			$client->setAccessToken($info['access_token']);
			$response = $client->fetch('http://www.example.com/protected_resource.php');
			echo $response;
		}
		else{
			print_r($response);
		}
	}
	else{
		print_r($response);
	}
}
