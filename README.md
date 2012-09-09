# Simple Single Sign-On Authentication Module for Moodle

<img style="float:left" src="https://github.com/downloads/marvinpinto/moodle-ssso/screenshot.png"/>

# Contents

 - [About](#about)
 - [Installation](#installation)
 - [Configuration](#configuration)
 - [Single Sign-On Consumer](#ssoconsumer)
 - [Contributing](#contributing)
 - [License](#license)
 - [Download](#download)
 - [Revision History](#revisionhistory)
 - [Author](#author)



<a name="about"></a>
## About

This plugin gives you the ability to issue custom SSO cookies (from
Moodle) so that other services/applications within the cookie domain may
authenticate and validate logged-in Moodle users.


### Background

For organizations that do not have a readily available CAS, Shibboleth, or
similar environment, achieving true (roaming) single sign-on with Moodle isn't
as easy as it should be.

This authentication module is geared for environments where roaming SSO is
needed between Moodle (acting as the source) and custom (modifiable)
applications.


### Moodle Compatibility

This module has been tested to work with Moodle 2.1.1+ (Build: 20110916), but
should reasonably work with any Moodle 2.x.x installation. Please [let me
know](https://github.com/marvinpinto/moodle-ssso/issues) if this isn't the case.



<a name="installation"></a>
## Installation

    $ cd /path/to/your/moodle/installation
    $ cd auth
    $ git clone https://github.com/marvinpinto/moodle-ssso.git ssso

Then log in as a user with administrator rights and go to `Site Administration
-> Notifications` where you will install this like any other Moodle plugin.

### Mcrypt

Note that since this plugin uses PHP's `mcrypt_encrypt` function, the
corresponding `mcrypt` module will need to be installed if not already
present. On Debian based systems, it's a simple matter of:

    $ apt-get install php5-mcrypt
    $ /etc/init.d/apache2 restart



<a name="configuration"></a>
## Configuration

After successfully installing this module, log in as a user with administrator
rights and go to `Site Administration -> Plugins -> Authentication -> Manage
Authentication`. Proceed to _enable_ the module named `Simple Single Sign-On
(SSSO)`. Ensure that this module is given the **lowest** priority in the
`Available authentication plugins` list.


### Settings

 - `Cookie name`: The name that will be used to distinguish this cookie from
   others.

 - `Path`: Used to identify the scope of the cookie, see
   [Wikipedia](http://en.wikipedia.org/wiki/HTTP_cookie#Domain_and_Path) for
   more information. Do not change the default value of `/` unless you know what
   you're doing!

 - `Domain`: Once again used to identify the scope of the cookie, see
   [Wikipedia](http://en.wikipedia.org/wiki/HTTP_cookie#Domain_and_Path) for
   more information. Remember that in order for SSO to work, both Moodle and the
   cookie consumer (application) need to belong to the same domain.

 - `Expiry`: Cookie validity time in hours.

 - `Secret`: Secret passphrase (salt) which will be used to encrypt and decrypt
   the shared cookie. Have a look at [this salt
   generator](http://dev.moodle.org/gensalt.php) for some nifty values.


<a name="ssoconsumer"></a>
## Single Sign-On Consumer

### Overview

In order for a user to seamlessly roam between two SSSO enabled systems, the
following protocol will need to be adhered to:

 1. Consumer will read in and decrypt the domain cookie using the supplied
 passphrase (salt).

 2. Consumer will first verify that the user's actual IP address matches the
 value specified in the cookie.

 3. Consumer will then verify that the cookie's actual expiry date matches the
 value specified in the cookie's encrypted data.

 4. If either of `2` or `3` fail, consider this is a bogus login attempt and
 redirect the user to the appropriate login page.

 5. It goes without saying but if this cookie is _not_ present, the user is not
 logged in and will need to be redirected or pointed to the appropriate login
 page.


### Cookie Format

    username=<moodle username>|IP=<a.b.c.d>|expiry=<unix epoch time>

 - Note that the expiry date/time in the encrypted payload is encoded as [Unix
   epoch time](http://en.wikipedia.org/wiki/Unix_time).

 - Also note that each field is delimited by the `|` (bar) character so it
   should go without saying that Moodle usernames should _not_ contain the `|`
   character.


### Moodle Login and Logout

This cookie is recreated and destroyed each time a user (actively) logs in and
out of Moodle. It is for this reason that users should be encouraged to
(actively) log out after their session is complete and administrators should
also try to keep cookie validity period reasonably low.


### Encryption and Decryption

The plaintext cookie is encrypted using AES 256, CBC mode. Here are a few
decryption examples:


#### PHP
```php
function decrypt($text, $salt) {
  return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text),
                             MCRYPT_MODE_CBC,
                             mcrypt_create_iv(mcrypt_get_iv_size
                                              (MCRYPT_RIJNDAEL_256,
                                               MCRYPT_MODE_CBC),
                                              MCRYPT_RAND))); 
}
$data = decrypt($_COOKIE['COOKIENAME'], 'longsecretsalt');
```


#### .NET

[Here](http://www.xuchao.org/docs/php/function.mcrypt-encrypt.html) is some good
information on decrypting `mcrypt` encrypted information. I'll get around to
posting a comprehensive .NET example eventually.



<a name="contributing"></a>
## Contributing

Please report issues on the [GitHub issue
tracker](https://github.com/marvinpinto/moodle-ssso/issues). Patches are
preferred as GitHub pull requests. Please use topic branches when sending pull
requests rather than committing directly to master in order to minimize
unnecessary merge commit clutter.



<a name="license"></a>
## License

```
Copyright 2012 Marvin Pinto (me@marvinp.ca)

Licensed under the Apache License, Version 2.0 (the "License"); you may
not use this file except in compliance with the License. You may obtain
a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
```


<a name="download"></a>
## Download

 - Zip: [MoodleSSSO-20120909.zip](https://github.com/downloads/marvinpinto/moodle-ssso/MoodleSSSO-20120909.zip)
 - Gzip: [MoodleSSSO-20120206.tar.gz](https://github.com/downloads/marvinpinto/moodle-ssso/MoodleSSSO-20120909.tar.gz)
 - Source code:
   [marvinpinto/moodle-ssso](https://github.com/marvinpinto/moodle-ssso)



<a name="revisionhistory"></a>
## Revision History

 - 20120909 (Sep 9, 2012 - Initial version)


<a name="author"></a>
## Author

 - Name: Marvin Pinto
 - Email: `me@marvinp.ca`
 - This is me on:
   - [Stack Overflow](http://stackoverflow.com/users/1101070)
   - [Google+](https://plus.google.com/110875969387062278975/posts)
   - [Twitter](https://twitter.com/marvinpinto)

