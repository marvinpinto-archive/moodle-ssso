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

_Don't forget to set the appropriate web permissions on the `auth/ssso`
  directory._



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

 - `Secret`: Secret 32 bit passphrase which will be used to encrypt and decrypt
   the shared cookie. Have a look at this [PC Tools
   generator](http://www.pctools.com/guides/password/?length=32&alpha=on&mixedcase=on&numeric=on&quantity=15&generate=true#password_generator)
   for some interesting ideas.

 - `Salt`: 32 bit salt which will be used to encrypt and decrypt the shared
   cookie. Have a look at this [PC Tools
   generator](http://www.pctools.com/guides/password/?length=32&alpha=on&mixedcase=on&numeric=on&quantity=15&generate=true#password_generator)
   for some interesting ideas.


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

    username=<moodle username>|email=<full email>|IP=<a.b.c.d>|expiry=<unix epoch time>

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


### Security Considerations

The major security concern associated with a scheme using cookies is [session
hijacking](http://en.wikipedia.org/wiki/Session_hijacking). This is alleviated
right off the bat by using HTTPS and _also_ encrypting the cookie contents
(separately).

The main purpose of encrypting the contents of the cookie is to thwart "casual
snoopers" and combined with the following guidelines should (theoretically)
reduce the probability of a session hijacking (or similar cookie related
exploit) to negligible levels:

 - **Change the salt passphrase at least once a month.**

 - Minimize the cookie validity as much as possible.

 - Respect the [SSSO
   protocol](https://github.com/marvinpinto/moodle-ssso#overview).


### Encryption and Decryption

The plaintext cookie can be encrypted using either OpenSSL DES in CBC mode or
AES256. Look through the
[lib.php](https://github.com/marvinpinto/moodle-ssso/blob/master/lib.php) for
the relevant functions.


#### PHP

Have a look through the
[lib.php](https://github.com/marvinpinto/moodle-ssso/blob/master/lib.php) for
PHP examples.


#### .NET

Here's an ASP.NET (C#) example copied verbatim from [this](http://vivavivugeek.blogspot.ca/2012/05/encrypt-by-php-decrypt-by-aspnet-c-and.html) page:

```c#
public static string DecryptRJ256(string prm_key, string prm_iv,
                                  string prm_text_to_decrypt) {

  var sEncryptedString = prm_text_to_decrypt;

  var myRijndael = new RijndaelManaged() {
      Padding = PaddingMode.Zeros,
        Mode = CipherMode.CBC,
        KeySize = 256,
        BlockSize = 256
        };

  var key = Encoding.ASCII.GetBytes(prm_key);
  var IV = Encoding.ASCII.GetBytes(prm_iv);

  var decryptor = myRijndael.CreateDecryptor(key, IV);

  var sEncrypted = Convert.FromBase64String(sEncryptedString);

  var fromEncrypt = new byte[sEncrypted.Length];

  var msDecrypt = new MemoryStream(sEncrypted);
  var csDecrypt = new CryptoStream(msDecrypt, decryptor, CryptoStreamMode.Read);

  csDecrypt.Read(fromEncrypt, 0, fromEncrypt.Length);

  return (Encoding.ASCII.GetString(fromEncrypt));
}

public static string EncryptRJ256(string prm_key, string prm_iv,
                                  string prm_text_to_encrypt) {

  var sToEncrypt = prm_text_to_encrypt;

  var myRijndael = new RijndaelManaged() {
      Padding = PaddingMode.Zeros,
        Mode = CipherMode.CBC,
        KeySize = 256,
        BlockSize = 256
        };

  var key = Encoding.ASCII.GetBytes(prm_key);
  var IV = Encoding.ASCII.GetBytes(prm_iv);

  var encryptor = myRijndael.CreateEncryptor(key, IV);

  var msEncrypt = new MemoryStream();
  var csEncrypt = new CryptoStream(msEncrypt, encryptor, CryptoStreamMode.Write);

  var toEncrypt = Encoding.ASCII.GetBytes(sToEncrypt);

  csEncrypt.Write(toEncrypt, 0, toEncrypt.Length);
  csEncrypt.FlushFinalBlock();

  var encrypted = msEncrypt.ToArray();

  return (Convert.ToBase64String(encrypted));
}


protected void Page_Load(object sender, EventArgs e) {
  //Shared 256 bit Key and IV here

  //32 chr shared ascii string (32 * 8 = 256 bit)
  string sKy = " lkirwf897+22#bbtrm8814z5qq=498j5 ";

  //32 chr shared ascii string (32 * 8 = 256 bit)
  string sIV = " 741952hheeyy66#cs!9hjv887mxx7@8y ";

  if(Request.QueryString["t"]!=null) {
    string t = Request.QueryString["t"].ToString();
    t = t.Trim().Replace(" ", "+");
    Button1.Text = DecryptRJ256(sKy, sIV, t);
  }
}
```



### Example

Using the following _key_ and _salt_ values:

 - Key: `9iebLEDo8SiunOewl1ciE7lAvoaQlekI`
 - Salt: `biastO6fIu02Az8APrletIuY0u36A1IU`

**AES256** encrypted cookie:

    T5Ew+cLmle3wcubDbcrx/dDN374ntABXJpqj+CX9Twy58PaTi0DOu2e0i2WeQIIlyBzk97sSUbKYbTQ0VnppJToAkaQn5nQrAVM/9HUewa3jO2mCqHsZXbF9faCl50YO

**OpenSSL** encrypted cookie:

    OfRbBd/Cx+mGlwVoxN/Dh/wGxMVNF5UaIxXlIrNOIzL4h3r27HjjKQzRzS8TLzTDdbEBb96narCWsLnOx6w+xrCZF3S/6FoOBEDNw4VciDkAz8V4z/WVKg==

**Decrypted** contents:

    username=admin|email=helpdesk@schulich.yorku.ca|IP=130.63.69.53|expiry=1348511353



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

 - Zip: [marvinpinto-moodle-ssso.zip](https://github.com/marvinpinto/moodle-ssso/zipball/master)
 - Tarball: [marvinpinto-moodle-ssso.zip](https://github.com/marvinpinto/moodle-ssso/tarball/master)
 - Source code:
   [marvinpinto/moodle-ssso](https://github.com/marvinpinto/moodle-ssso)


<a name="author"></a>
## Author

 - Name: Marvin Pinto
 - Email: `me@marvinp.ca`
 - This is me on:
   - [Stack Overflow](http://stackoverflow.com/users/1101070)
   - [Google+](https://plus.google.com/110875969387062278975/posts)
   - [Twitter](https://twitter.com/marvinpinto)

