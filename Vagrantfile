# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT
cd /var/www/web/api/v1 && composer install
cd /var/www/web/api/v1/cron && composer install
sudo sed -i 's#DocumentRoot /var/www/public#DocumentRoot /var/www/web#' /etc/apache2/sites-available/000-default.conf
sudo /etc/init.d/apache2 restart
mysql -uroot -proot scotchbox < /var/www/Database/*.sql
cd /var/www/
wget https://phar.phpunit.de/phpunit.phar
chmod +x phpunit.phar
sudo mv phpunit.phar /usr/local/bin/phpunit
sudo chmod +x /usr/local/bin/phpunit
SCRIPT

Vagrant.configure("2") do |config|

  config.vm.box = "scotch/box"
  config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.hostname = "scotchbox"
  config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=666"]
  config.vm.provision "shell", inline: $script

end
