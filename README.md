# stock

## 需求
* Redis
* PHP 7.1
* MySQL
* NPM
* Vue.js
* Composer
* FB Developer 

## 安裝
```
git clone
composer install
npm install
npm run dev
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
redis-server
```


## file .env 設定
```
FB_ID=
FB_SECRET=
```

## facebook callbak url
```
domain/facebook-callback
```
