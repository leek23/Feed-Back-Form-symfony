# Feed-Back-Form-symfony

```
git pull
composer install
```

next step is setup .env file
set your database connection settings here

```
DATABASE_URL=mysql://homestead:secret@127.0.0.1:3306/test
```
and last step
```
php bin/console doctrine:migrations:migrate
```
