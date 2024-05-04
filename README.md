# Installation
#### Installing the package
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

# Associate comments with Eloquent models

This package allows your models the ability to have comments associated to it.

Here's a small example of how to add comment to your model.

Lets say you have a Post model. In order to make it commentable, you just attach the HasComment trait to it

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
return $post->getComments(int $pagination_limit, bool $with_reaction_count); 
```

# Attach reaction to an Eloquent model

You can also attach reaction to any model. For that, just use:
```php
use BishalGurung\Comment\Traits\HasReaction;

class Post extends Model
{
    use HasReaction;
}
```

Now to add a reaction to a model, just use:
```php
$post = Post::find(1);
$post->react($reaction_type_id); // The primary key i.e. "id" from reaction_types table
```

Now to retrieve the reaction counts along with the Post model, we use:
```php
$posts = Post::with("reactionCount")->get();
```

This will return reaction count attached to Post collection as below:
```json
{
    "reaction_count": [
        {
            "model_id": "1",
            "reaction_type_id": 1,
            "type": "like",
            "count": 3
        },
        {
            "model_id": "1",
            "reaction_type_id": 2,
            "type": "love",
            "count": 5
        }
    ]
}
```

# Attach reply to a comment
If you take a look at the comment model in the _**vendor/bishalgurung/laravel-comment/src/Models/Comment.php**_ file, you will notice that the Comment model also uses the **_HasComment_** trait. Which means that you can comment on a comment model. So a comment on a comment model is a reply, and this is how you use the reply system.

```php
    $comment = Comment::find(1);
    $comment->addComment("This is a reply");
```
And to get replies, just use:
```php
    $comment->getComments(int $pagination_limit, bool $with_reaction_count); 
```
# Upcoming feature
- Custom icons for your reaction

