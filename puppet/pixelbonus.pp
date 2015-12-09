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

# ensure apache2 service is running
service { 'apache2':
  ensure => running,
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

# wkhtmltopdf
package { 'wkhtmltopdf':
  require => Exec['apt-update'],        # require 'apt-update' before installing
  ensure => installed,
}

exec { 'composer-update':
  command => "composer update --no-interaction",
  path    => '/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin',
  cwd     => '/var/www/pixelbonus',
  environment => [ "COMPOSER_HOME=/usr/local/bin" ],
  require => [ Class['composer'] ],
}