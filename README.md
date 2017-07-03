# Avatar Manager
[![StyleCI](https://styleci.io/repos/94704466/shield?branch=master)](https://styleci.io/repos/94704466)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d84efcf2530348d29f2ca573d06f7314)](https://www.codacy.com/app/laravel-enso/AvatarManager?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/AvatarManager&utm_campaign=badger)
[![License](https://poser.pugx.org/laravel-enso/avatarmanager/license)](https://https://packagist.org/packages/laravel-enso/avatarmanager)
[![Total Downloads](https://poser.pugx.org/laravel-enso/avatarmanager/downloads)](https://packagist.org/packages/laravel-enso/avatarmanager)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/avatarmanager/version)](https://packagist.org/packages/laravel-enso/avatarmanager)

User Avatar manager dependency for [Laravel Enso](https://github.com/laravel-enso/Enso).

[![Watch the demo](https://laravel-enso.github.io/avatarmanager/screenshots/Selection_013.png)](https://laravel-enso.github.io/avatarmanager/videos/avatar_change.webm)
<sup>click on the photo to view a short demo in compatible browsers</sup>

## Details
 
Includes:
- a table migration, in order to be able to store avatar related data
- model, routes & controllers
- a folder used to store the avatar files and a default avatar for users that do not have an avatar set

Uses the [File Manager](https://github.com/laravel-enso/AvatarManager) package for uploading the avatar files.

Uses the [Image Transformer](https://github.com/laravel-enso/ImageTransformer) package for cropping and optimizing the avatar files.

## Notes

The [Laravel Enso Core](https://github.com/laravel-enso/Core) package comes with this package included.

## Publishes

- `php artisan vendor:publish --tag=avatars-storage` - storage folder and default avatar

### Contributions

are welcome