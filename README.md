# Installation
#### Requiring the dependency
```bash
composer require bishalgurung/laravel-comment
```
#### Exporting the migration files:

```bash
php artisan vendor:publish --provider="BishalGurung\Comment\CommentServiceProvider" --tag="migration"
```
#### Migrating the tables
```bash
php artisan migrate
```
#### Seeding default reactions:
```bash
php artisan comment:install
```
You can add more reactions by changing the "reactions" array in the **_comment.php_** config file and running **_comment:install_** again

#### Exporting the config file (optional):

```bash
php artisan vendor:publish --provider="BishalGurung\Comment\CommentServiceProvider" --tag="config"
```
The follwing will publish the config file below
```php
<?php

return [
    "reactions" => [
        "like",
        "love",
        "dislike",
        "wow"
    ]
];

```

# Associate comments and reactions with Eloquent models

This package allows your models the ability to have comments associated to it.

Here's a small example of how to add comment to your model.

Lets say you have a Post model. Inorder to make it commentable, you just attach the HasComment trait to it

```php
use BishalGurung\Comment\Traits\HasComments;

class Post extends Model
{
    use HasComments;
}
```
Now to add a comment, just use:
```php
$post = Post::find(1);
$post->addComment("Hey there, this is how you add a comment");
```
By default, the logged in user will be set as the user_type and user_id in the _**comments**_ table.
But you can change that by using:
```php
$post->setCommentUser($user)->addComment("Hey there, this is how you add a comment but set the user manually");
```

If you want to retrieve all the comments of a post, use:
```php
$post = Post::find(1);
return $post->getComments();
```
You can also attach reaction to a comment. For that, just use:
```php
use BishalGurung\Comment\Models\Comment;

$comment = Comment::find(1);
$comment->react(1); // reaction id as the parameter
```
The reactions count will be returned along with getComments() function above

# Upcoming feature
- Ability to attach reaction to any of your eloquent model and not just Comment model
- Custom icons for your reaction

