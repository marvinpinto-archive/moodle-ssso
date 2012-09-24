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
  die('Direct access to this script is forbidden.');    //  It must be included
                                                        // from a Moodle page
}

global $CFG;
?>
<table cellpadding="9" cellspacing="0" >

  <tr valign="top">
  <td align="right"><?php print_string('ssso_cookiename_label', 'auth_ssso') ?></td>
  <td><input name="ssso_cookiename" type="text" value="<?php echo htmlspecialchars($config->ssso_cookiename) ?>" /></td>
  <td><?php print_string('ssso_cookiename_desc', 'auth_ssso') ?></td>
  </tr>


  <tr valign="top">
  <td align="right"><?php print_string('ssso_cookiepath_label', 'auth_ssso') ?></td>
  <td><input name="ssso_cookiepath" type="text" value="<?php echo htmlspecialchars($config->ssso_cookiepath) ?>" /></td>
  <td><?php print_string('ssso_cookiepath_desc', 'auth_ssso') ?></td>
  </tr>


  <tr valign="top">
  <td align="right"><?php print_string('ssso_cookiedomain_label', 'auth_ssso') ?></td>
  <td><input name="ssso_cookiedomain" type="text" value="<?php echo htmlspecialchars($config->ssso_cookiedomain) ?>" /></td>
  <td><?php print_string('ssso_cookiedomain_desc', 'auth_ssso') ?></td>
  </tr>


  <tr valign="top">
  <td align="right"><?php print_string('ssso_cookieexpiry_label', 'auth_ssso') ?></td>
  <td><input name="ssso_cookieexpiry" type="text" value="<?php echo htmlspecialchars($config->ssso_cookieexpiry) ?>" /></td>
  <td><?php print_string('ssso_cookieexpiry_desc', 'auth_ssso') ?></td>
  </tr>


  <tr valign="top">
  <td align="right"><?php print_string('ssso_cookiesecret_label', 'auth_ssso') ?></td>
  <td><input name="ssso_cookiesecret" type="password" value="<?php echo htmlspecialchars($config->ssso_cookiesecret) ?>" /></td>
  <td><?php print_string('ssso_cookiesecret_desc', 'auth_ssso') ?></td>
  </tr>


  <tr valign="top">
  <td align="right"><?php print_string('ssso_cookiesalt_label', 'auth_ssso') ?></td>
  <td><input name="ssso_cookiesalt" type="text" value="<?php echo htmlspecialchars($config->ssso_cookiesalt) ?>" /></td>
  <td><?php print_string('ssso_cookiesalt_desc', 'auth_ssso') ?></td>
  </tr>


  <tr><td><br/></td></tr>

  <tr valign="top">
  <td align="right"><?php print_string('ssso_update_label', 'auth_ssso') ?></td>
  <td colspan="2">
  <p><?php print_string('ssso_update_desc', 'auth_ssso') ?></p></td>
  </tr>
  </table>

