<?php

$this->encryption->decrypt($value);
return array(
  'authorizationRequestUrl' => 'https://appcenter.intuit.com/connect/oauth2', //Example https://appcenter.intuit.com/connect/oauth2',
  'tokenEndPointUrl' => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer', //Example https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
  'client_id' => get_option(''), //Example 'Q0wDe6WVZMzyu1SnNPAdaAgeOAWNidnVRHWYEUyvXVbmZDRUfQ',
  'client_secret' => get_option(''), //Example 'R9IttrvneexLcUZbj3bqpmtsu5uD9p7UxNMorpGd',
  'oauth_scope' => 'com.intuit.quickbooks.accounting', //Example 'com.intuit.quickbooks.accounting',
  'openID_scope' => 'openid profile email', //Example 'openid profile email',
  'oauth_redirect_uri' => admin_url('accounting/quickbook_connect'), //Example https://d1eec721.ngrok.io/OAuth_2/OAuth2PHPExample.php',
  'openID_redirect_uri' => admin_url('accounting/quickbooks2'),//Example 'https://d1eec721.ngrok.io/OAuth_2/OAuthOpenIDExample.php',
  'mainPage' => admin_url('accounting/quickbooks_index'), //Example https://d1eec721.ngrok.io/OAuth_2/index.php',
  'refreshTokenPage' => admin_url('accounting/quickbooks_refreshToken'), //Example https://d1eec721.ngrok.io/OAuth_2/RefreshToken.php'
)
?>