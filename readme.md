# Pascal Sommer Photography website

[My photography blog](https://photography.pascalsommer.ch), now written in Laravel

## Development Setup

```bash
# Create .env file from example
cp laravel/.env{.example,}

# Let the server write to these directories
sudo chmod a+w -R laravel/storage/{framework,logs}
sudo chmod a+w -R img/photos/
sudo chmod a+w -R img/thumbs/

# Download the photos that are currently uploaded to the server
rsync -rvih --info=progress2 gegubiha:www/pascalsommer.ch/photography/img/photos/ img/photos/
rsync -rvih --info=progress2 gegubiha:www/pascalsommer.ch/photography/img/thumbs/ img/thumbs/

# Quickly set up a mysql server
sudo docker run --rm -d --name blog-mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=blog mysql
sudo docker exec -i blog-mysql sh -c 'exec mysql -uroot -proot blog' < dump.sql

# Start a container in which we can use yarn and npx
sudo docker run --rm -it --name blog-node -v $PWD:/home/node/app node bash
# su node
# cd ~/app/laravel
# yarn
# npm run prod

# Generate SVG icons
cd laravel/tools/generate-icons
cargo run

# Update php dependencies
sudo docker run --rm -it --name blog-composer -v $PWD/laravel:/app/ composer update

# Run a local php server
sudo tempcontainerz-php
```

At this point you might have to run `compser dump-autoload` on the server, or if php7 is still the default on hostpoint then `/usr/local/php81/bin/composer dump-autoload`.

## Front-End

The js and sass source files are located in `laravel/resources/`, and the generated output will go to the `/js`, `/css`, and `/fonts` directories. To compile, type:

```bash
cd laravel
yarn
npm run prod
```

Note that `/js/keyboardShortcuts.js` and `/js/setPhotoDimensions.js` are currently not generated, but rather developed there in place. This will hopefully change soon(ish).

## Todo
- [ ] visual indication for tags that reduce the nr. of results to zero when selected
- [ ] generate keyboardShortcuts.js and setPhotoDimensions.js through typescript
- [ ] store photo dimensions in db or cache them somewhere
- [ ] remove exif from photos
- [ ] reencode images with progressive jpg
- [ ] replace uploaded photo from file, keeping all other data
- [ ] add alt-text according to [these guidelines](https://axesslab.com/alt-texts/) to photos, add alt-text missing filter to staging view
- [ ] maybe change how the filters work in staging view
- [ ] pagination for staging view?
- [ ] check if title is unique in "new post" frontend via some js or something like that
- [ ] add location information to photos?
- [ ] make posts page load quicker
