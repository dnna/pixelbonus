# pixelbonus

## Installing via puppet
This repository contains a puppet script for setting up pixelbonus on a clean installation of Ubuntu Server 14.04.
To use it run the following commands while inside the "puppet" directory of the application:

 - apt-get install puppet
 - puppet module install puppetlabs/mysql
 - puppet module install puppetlabs/vcsrepo
 - puppet module install willdurand-composer
 - git clone [pixelbonus URL]
 - cd pixelbonus/puppet
 - puppet apply pixelbonus.pp
 - Change the secret key at /var/www/pixelbonus/app/config/parameters.yml

## Configuration
After installing pixelbonus, a few configuration parameters need to be set based on the particular setup. These include database and mailer configuration and a secret key to be used when encrypting the QR codes.

The configuration settings are located in **app/config/parameters.yml**. The file is in [YAML](https://en.wikipedia.org/wiki/YAML) format and contains the following options:
 - database_driver: One of the PHP PDO [database drivers](http://php.net/manual/en/pdo.drivers.php).
 - database_host: The database host. Usually localhost.
 - database_port: The database port. Defaults to 3306.
 - database_name: The database name. Defaults to pixelbonus.
 - database_user: The database user. Defaults to root.
 - database_password: The database password.
 - mailer_transport: One of swiftmailer's [compatible transports](http://swiftmailer.org/docs/sending.html#transport-types). Defaults to mail.
 - mailer_host: SMTP host (for smtp transport).
 - mailer_encryption: [SMTP encryption](http://swiftmailer.org/docs/sending.html#encrypted-smtp) (for smtp transport). Defaults to tls.
 - mailer_port: SMTP port (for smtp transport). Defaults to 587.
 - mailer_auth_mode: [SMTP authentication method](http://swiftmailer.org/docs/sending.html#smtp-with-a-username-and-password). Defaults to login.
 - mailer_user: SMTP user (for smtp transport).
 - mailer_password: SMTP password (for smtp transport).
 - locale: Default locale when not set explicitly by the user. Defaults to el.
 - secret: A random string used as the key for encrypting QR codes.
 - wkhtmltopdf: Absolute path to the wkhtmltopdf binary.

## Try it out
To try it out without installing visit http://www.pixelbonus.com
