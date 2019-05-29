## Course Management System

After clone or download the project, cd into project folder and run these commands:
```
composer install
```
```
cp .env.example .env
```
```
php artisan key:generate
```
```
php artisan jwt:secret
```

Change database credentials and run migrate command and seed database with dummy users, courses and lessons.
```
php artisan migrate --seed
```

Install npm packages
```
npm install
```
To get production version of js and css files, run
```
npm run prod
```

> Run server to test

```
php artisan serve
```
