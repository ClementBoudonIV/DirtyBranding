# DirtyBranding

## Installation

### Pré-requis :
- Virtualbox
- Vagrant
- [nodejs](https://nodejs.org/) (+ `npm config set prefix /usr/local`)
- [Bower](http://bower.io/)

### Puis :
- Ajouter dans le fichier host local :

| IP  | Host |
| ------------- | ------------- |
| 192.168.33.10  | api.dirtybranding.com  |
| 192.168.33.10  | app.dirtybranding.com  |
| 192.168.33.10  | www.dirtybranding.com  |
| 192.168.33.10  | dirtybranding.com  |
- `vagrant up`
- `cd web/app/web`
- `bower install`

## Utilisation
- `vagrant up` (si ce n'est déjà fait)
- [http://app.dirtybranding.com](http://app.dirtybranding.com/)

## Database Access

| Key  | Value |
| ------------- | ------------- |
| Database Name  | scotchbox  |
| Database User  | root  |
| Database Password  | root  |
| Database Host  | localhost / 127.0.0.1  |
| SSH Host  | 192.168.33.10  |
| SSH User  | vagrant  |
| SSH Password  | vagrant  |

## Testing

### API
- `vagrant ssh`
- Charger la base de données (Structure dans `/Database`)
- phpunit -c web/api/web/v1/phpunit.xml.dist

