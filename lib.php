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


function ssso_get_cookie_data($key, $salt, $username, $ip, $expiry, $email) {
  $data = '';
  $data .= 'username=' .$username. '|';
  $data .= 'email=' .$email. '|';
  $data .= 'IP=' .$ip. '|';
  $data .= 'expiry=' .$expiry;

  /* // Encrypt-decrypt using openssl */
  /* $enc_val = encrypt_openssl($key, $salt, $data); */
  /* debugging('Key: ' .$key. ' Salt: ' .$salt); */
  /* debugging('Plain: ' .$data); */
  /* debugging('Encrypted: ' .$enc_val); */
  /* debugging('Decrypted: ' .decrypt_openssl($key, $salt, $enc_val)); */

  // Encrypt-decrypt using mcrypt
  $enc_val = encrypt_RJ256($key, $salt, $data);
  /* debugging('Key: ' .$key. ' Salt: ' .$salt); */
  /* debugging('Plain: ' .$data); */
  /* debugging('Encrypted: ' .$enc_val); */
  /* debugging('Decrypted: ' .decrypt_RJ256($key, $salt, $enc_val)); */

  return $enc_val;
}


function encrypt_openssl($key, $salt, $data) {
  $enc_val = openssl_encrypt($data, 'des-cbc', $key, false);
  $retval = trim($enc_val);
  return $retval;
}


function decrypt_openssl($key, $salt, $data) {
  $dec_val = openssl_decrypt($data, 'des-cbc', $key, false);
  $retval = trim($dec_val);
  return $retval;
}


function encrypt_RJ256($key, $salt, $data) {
  $enc_val = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data,
                            MCRYPT_MODE_CBC, $salt);
  $retval = base64_encode($enc_val);
  return $retval;
}


function decrypt_RJ256($key, $salt, $data) {
  $data = base64_decode($data);
  $dec_val = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $data,
                            MCRYPT_MODE_CBC, $salt);
  $retval = rtrim($dec_val, "\0\4");
  return $retval;
}

?>
