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

if (!defined('MOODLE_INTERNAL')) {
  die('Direct access to this script is forbidden.');    // It must be included
                                                        // from a Moodle page
}


function ssso_get_cookie_value($key, $username, $ip, $expiry, $email) {
  $mval = '';
  $mval .= 'username=' .$username. '|';
  $mval .= 'email=' .$email. '|';
  $mval .= 'IP=' .$ip. '|';
  $mval .= 'expiry=' .$expiry;

  $enc_val = trim(base64_encode(openssl_encrypt($mval, 'des-cbc', $key)));
  return $enc_val;
}

function decrypt($text, $salt) {
  $dec_val = trim(openssl_decrypt(base64_decode($text), 'des-cbc', $salt));
  return $dec_val;
}

?>
