<?php

/*
  Copyright 2012 Marvin Pinto (me@marvinp.ca)

  Licensed under the Apache License, Version 2.0 (the "License"); you may not
  use this file except in compliance with the License.  You may obtain a copy of
  the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
  WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
  License for the specific language governing permissions and limitations under
  the License.
*/

$string['auth_sssotitle'] = 'Simple SSO Configuration';
$string['pluginname'] = 'Simple Single Sign-On (SSSO)';
$string['auth_sssodescription'] = 'This plugin allows Moodle to issue custom SSO cookies so that other services/applications within the cookie domain may authenticate and validate logged-in Moodle users.';

$string['ssso_cookiename_label'] = 'Cookie name';
$string['ssso_cookiename_desc'] = 'Name assigned to the SSO cookie';
$string['ssso_cookiename_default'] = 'SimpleSSOCookie';

$string['ssso_cookiepath_label'] = 'Path';
$string['ssso_cookiepath_desc'] = 'Cookie path (<a href="http://en.wikipedia.org/wiki/HTTP_cookie#Domain_and_Path" target="_blank">more info</a>)';
$string['ssso_cookiepath_default'] = '/';

$string['ssso_cookiedomain_label'] = 'Domain';
$string['ssso_cookiedomain_desc'] = 'Cookie domain (<a href="http://en.wikipedia.org/wiki/HTTP_cookie#Domain_and_Path" target="_blank">more info</a>)';
$string['ssso_cookiedomain_default'] = 'example.org';

$string['ssso_cookieexpiry_label'] = 'Expiry';
$string['ssso_cookieexpiry_desc'] = 'Cookie validity (in hours)';
$string['ssso_cookieexpiry_default'] = 2;

$string['ssso_cookiesecret_label'] = 'Secret';
$string['ssso_cookiesecret_desc'] = 'The shared secret used to encrypt/decrypt SSO cookies';
$string['ssso_cookiesecret_default'] = 'super$ecretPwd';

$string['ssso_cookiesalt_label'] = 'Salt';
$string['ssso_cookiesalt_desc'] = 'The shared salt value';
$string['ssso_cookiesalt_default'] = '2EAEDD563B91B6D5458F53A5086F4594';

$string['ssso_update_label'] = 'Info:';
$string['ssso_update_desc'] = 'For more information and updates, please visit the Simple Single Sign-On project page on GitHub: <a href="https://github.com/marvinpinto/moodle-ssso" target="_blank">github.com/marvinpinto/moodle-ssso</a>';

?>
