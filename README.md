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
