<!--h-->
# Avatar Manager

[![StyleCI](https://styleci.io/repos/94704466/shield?branch=master)](https://styleci.io/repos/94704466)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d84efcf2530348d29f2ca573d06f7314)](https://www.codacy.com/app/laravel-enso/AvatarManager?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/AvatarManager&utm_campaign=badger)
[![License](https://poser.pugx.org/laravel-enso/avatarmanager/license)](https://https://packagist.org/packages/laravel-enso/avatarmanager)
[![Total Downloads](https://poser.pugx.org/laravel-enso/avatarmanager/downloads)](https://packagist.org/packages/laravel-enso/avatarmanager)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/avatarmanager/version)](https://packagist.org/packages/laravel-enso/avatarmanager)
<!--/h-->

User Avatar manager dependency for [Laravel Enso](https://github.com/laravel-enso/Enso).

[![Watch the demo](https://laravel-enso.github.io/avatarmanager/screenshots/bulma_cap001_thumb.png)](https://laravel-enso.github.io/avatarmanager/videos/bulma_avatar_change.webm)
<sup>click on the photo to view a short demo in compatible browsers</sup>

## Features

- comes with a table migration, in order to be able to store avatar related data
- includes model, routes & controllers
- creates a folder used to store the avatar files and a default avatar for users that do not have an avatar set
- uses the [File Manager](https://github.com/laravel-enso/FileManager) package for uploading the avatar files.
- uses the [Image Transformer](https://github.com/laravel-enso/ImageTransformer) package for cropping and optimizing the avatar files.

## Publishes

- `php artisan vendor:publish --tag=avatars-storage` - storage folder and default avatar

### Notes

The [Laravel Enso](https://github.com/laravel-enso/Enso) package comes with this package included.

Depends on:
 - [Core](https://github.com/laravel-enso/Core) for middleware and user model 
 - [ImageTransformer](https://github.com/laravel-enso/ImageTransformer) for the optimization of avatar images
 - [FileManager](https://github.com/laravel-enso/FileManager) for managing the image files
 - [Structure manager](https://github.com/laravel-enso/StructureManager) for the migrations


<!--h-->
### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
<!--/h-->