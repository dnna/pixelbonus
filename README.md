# pixelbonus

## Installing via puppet
This repository contains a puppet script for setting up pixelbonus on a clean installation of Ubuntu Server 14.04.
To use it run the following commands while inside the "puppet" directory of the application:

 - puppet module install puppetlabs/mysql
 - puppet module install puppetlabs/vcsrepo
 - puppet module install willdurand-composer
 - puppet apply pixelbonus.pp