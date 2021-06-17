# SimplonBlogSymfony_5.3

Mon second projet Symfony chez Simplon

## Installation

Allez dans votre répertoire préféré

```bash
git clone https://github.com/democvidev/SimplonBlogSymfony_5.3.git
cd SimplonBlogSymfony_5.3
composer install
```
Changez le nom fu fichier ".env copy" en ".env"
Configurez la connexion au MySQL. Par exemple : 
DATABASE_URL="mysql://root:@127.0.0.1:3306/db_symfony_blog?serverVersion=5.7"

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
Enjoy
