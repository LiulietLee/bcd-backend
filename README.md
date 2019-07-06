# bcd-backend
> New Bilibili Cover Downloader Backend

## How To Use
- Get code
```
git clone https://github.com/LiulietLee/bcd-backend
cd bcd-backend
```
- Install libraries
```
composer install
```
- Config env file
```
nano .env
```
- Create database and table
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
- Run local server
```
php bin/console server:run
```

## API List
```
/api/search?type=[stringType]&nid=[numberID]
/api/hot_list
/api/db/search?type=[stringType]&nid=[numberID]
/api/db/update (post json: {'type': [stringType], 'nid': [numberID], 'url': [coverURL], 'title': [videoTitle], 'author': [authorName]})
/api/comment/new (post josn: {'username': [username], 'content': [content]})
```

## Cover Spider
https://github.com/ApolloZhu/Vaporized-BilibiliCD

## TODO
- [ ] PHP version spider
- [ ] Better frontend UI
