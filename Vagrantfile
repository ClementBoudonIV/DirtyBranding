# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT
sudo sed -i 's#DocumentRoot /var/www/public#DocumentRoot /var/www/web#' /etc/apache2/sites-available/000-default.conf
sudo /etc/init.d/apache2 restart
SCRIPT

Vagrant.configure("2") do |config|

  config.vm.box = "scotch/box"
  config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.hostname = "scotchbox"
  config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=666"]
  config.vm.provision "shell", inline: $script

end
