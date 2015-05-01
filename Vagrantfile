# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT
export APP_ENV=dev
cd /var/www/web/api/web/v1 && composer install
cd /var/www/web/api/web/v1/cron && composer install
sudo cp /var/www/vhost/*.conf /etc/apache2/sites-available/
sudo cp /var/www/vhost/*.conf /etc/apache2/sites-enabled/
sudo rm /etc/apache2/sites-available/000-default.conf
sudo rm /etc/apache2/sites-enabled/000-default.conf
echo '127.0.0.1 api.dirtybranding.com' | sudo tee --append /etc/hosts > /dev/null
echo '127.0.0.1 app.dirtybranding.com' | sudo tee --append /etc/hosts > /dev/null
echo '127.0.0.1 www.dirtybranding.com' | sudo tee --append /etc/hosts > /dev/null
sudo /etc/init.d/apache2 restart
mysql -uroot -proot scotchbox < /var/www/Database/DumpStructure_DB_API.sql
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
