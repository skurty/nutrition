# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "Debian Jessie 8.0 Release x64 (Minimal, Shrinked, Guest Additions 4.3.26)"

  config.vm.box_url = "https://github.com/holms/vagrant-jessie-box/releases/download/Jessie-v0.1/Debian-jessie-amd64-netboot.box"

  config.vm.hostname = "vagrant-nutrition"

  config.vm.network "private_network", ip: "192.168.11.5"

  config.vm.synced_folder "./", "/var/www/nutrition"

  config.vm.provision :shell, path: "bootstrap.sh"

  config.vm.network :forwarded_port, guest: 80, host: 4567
end
