# Requires
# puppet module install puppetlabs/mysql
# puppet module install puppetlabs/vcsrepo
# puppet module install willdurand-composer

# execute 'apt-get update'
exec { 'apt-update':                    # exec resource named 'apt-update'
  command => '/usr/bin/apt-get update'  # command this resource will run
}

# install apache2 package
package { 'apache2':
  require => Exec['apt-update'],        # require 'apt-update' before installing
  ensure => installed,
}

exec { 'allow-override':
  command => "sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf",
  path    => '/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin',
  cwd     => '/etc/apache2',
  require => [ Package['apache2'] ],
}

exec { 'enable-mod-rewrite':
  command => "a2enmod rewrite; /etc/init.d/apache2 restart",
  path    => '/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin',
  cwd     => '/etc/apache2',
  require => [ Exec['allow-override'] ],
}

# ensure apache2 service is running
service { 'apache2':
  ensure => running,
  require => Exec['enable-mod-rewrite']
}

# install mysql-server package
package { 'mysql-server':
  require => Exec['apt-update'],        # require 'apt-update' before installing
  ensure => installed,
}

# ensure mysql service is running
service { 'mysql':
  ensure => running,
}

# install php5 package
package { 'php5':
  require => Exec['apt-update'],        # require 'apt-update' before installing
  ensure => installed,
}
package { 'php5-mysql':
  require => Exec['apt-update'],        # require 'apt-update' before installing
  ensure => installed,
}
package { 'php5-mcrypt':
  require => Exec['apt-update'],        # require 'apt-update' before installing
  ensure => installed,
}
exec { 'enable-mcrypt':
  command => "php5enmod mcrypt; /etc/init.d/apache2 restart",
  path    => '/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin',
  cwd     => '/etc/apache2',
  require => [ Package['php5-mcrypt'] ],
}

mysql::db { 'pixelbonus':
  user     => 'pixelbonus',
  password => 'pixelbonus',
  host     => 'localhost',
  grant    => ['SELECT', 'UPDATE'],
}

# install git package
package { 'git':
  require => Exec['apt-update'],        # require 'apt-update' before installing
  ensure => installed,
}

vcsrepo { "/var/www/pixelbonus":
    ensure   => latest,
    owner    => $owner,
    group    => $owner,
    provider => git,
    require  => [ Package["git"] ],
    source   => "https://github.com/dnna/pixelbonus.git",
    revision => 'master',
}

include composer

package { 'wkhtmltopdf':
  require => Exec['apt-update'],        # require 'apt-update' before installing
  ensure => installed,
}
package { 'xvfb':
  require => Exec['apt-update'],        # require 'apt-update' before installing
  ensure => installed,
}

exec { 'composer-update':
  command => "composer update --no-interaction",
  path    => '/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin',
  cwd     => '/var/www/pixelbonus',
  environment => [ "COMPOSER_HOME=/usr/local/bin" ],
  require => [ Class['composer'], Package['wkhtmltopdf'], Package['xvfb'] ],
}

exec { 'schema-update':
  command => "php app/console doctrine:schema:update --force",
  path    => '/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin',
  cwd     => '/var/www/pixelbonus',
  require => [ Exec['composer-update'] ],
}

exec { 'setup-document-root':
  command => "rm -fR /var/www/html; ln -s /var/www/pixelbonus/web /var/www/html",
  path    => '/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin',
  cwd     => '/var/www/pixelbonus',
  require => [ Exec['composer-update'] ],
}

exec { 'fix-permissions':
  command => "chown -R www-data:www-data /var/www/pixelbonus; chown -R www-data:www-data /var/www/html",
  path    => '/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin',
  cwd     => '/var/www/pixelbonus',
  require => [ Exec['setup-document-root'] ],
}