Laravel IdentityStamps Plugin
=============================
A Laravel plugin to register and keep control of users who make creations, updates and deletions of models.

With this plugin, you will manage automatically the control fields "created_by", "updated_by" and "deleted_by" that 
will store the identity of the users that manipulate the models.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

With Composer installed, you can then install the extension using the following commands:

```bash
$ php composer.phar require jlorente/laravel-identitystamps
```

or add 

```json
...
    "require": {
        "jlorente/laravel-identitystamps": "*"
    }
```

to the ```require``` section of your `composer.json` file.

## Configuration

Register the ServiceProvider in your config/app.php service provider list.

config/app.php
```php
return [
    //other stuff
    'providers' => [
        //other stuff
        \Jlorente\Laravel\IdentityStamp\IdentityStampServiceProvider::class,
    ];
];
```

## Usage

### On Migrations

You can use the Blueprint method identityStamps() to add nullable "created_by" 
and "updated_by" UNSIGNED INTEGER equivalent columns. Of course, you can create 
the columns by yourself with a custom name and type and then configure the Model 
class with these names, but remember that the type should be the same as the type 
of the key of your UserModel.

```php

class MyMigration extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_table', function (Blueprint $table) {
            $table->increments('id');
            $table->string('my_field');
            $table->timestamps();
            $table->identityStamps();
        });
    }
}
```

If you use soft deletes, maybe you want to add an identity stamp for the deletion too. 
You can do this by using the softDeletesIdentityStamps() method that will add a 
nullable "deleted_by" UNSIGNED INTEGER column.

```php

class MyMigration extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('my_field');
            $table->timestamps();
            $table->identityStamps();
            $table->softDeletes();
            $table->softDeletesIdentityStamps();
        });
    }
}
```

### Attaching the behavior to a Model

To enable identity stamps for a model, use the Jlorente\IdentityStamp\Eloquent\IdentityStamps trait on the model:

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jlorente\IdentityStamp\Eloquent\IdentityStamps;

class Product extends Model
{
    use IdentityStamps,
        SoftDeletes;
}
```

## Further considerations

### Using custom attributes names

You can use your custom attributes names to store the identity by defining class 
constants on the model.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jlorente\IdentityStamp\Eloquent\IdentityStamps;

class Product extends Model
{
    use IdentityStamps,
        SoftDeletes;

    const CREATED_BY = 'my_custom_identity_creation_field';
    const UPDATED_BY = 'my_custom_identity_update_field';
    const DELETED_BY = 'my_custom_identity_deletion_field';
}
```

If you don't like class constants you can also override the trait methods that 
resolve the identity stamp fields.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jlorente\IdentityStamp\Eloquent\IdentityStamps;

class Product extends Model
{
    use IdentityStamps,
        SoftDeletes;

    public function getCreatedByColumn() 
    {
        return 'my_custom_identity_creation_field';
    }

    public function getUpdatedByColumn() 
    {
        return 'my_custom_identity_update_field';
    }

    public function getDeletedByColumn() 
    {
        return 'my_custom_identity_deletion_field';
    }
}
```

### Using custom identity id to be stored in the identity fields

By default, the trait will use Laravel's Auth::id() method to retrieve the id 
that will be stored on the identity stamp fields. Feel free to override the 
method getIdentityStampValue() to return the value that you want to store in the 
fields.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Jlorente\IdentityStamp\Eloquent\IdentityStamps;

class Product extends Model
{
    use IdentityStamps,
        SoftDeletes;

    public function getIdentityStampValue() 
    {
        return Auth::user() ? Auth::user()->email : null;
    }
}
```

## License 

Copyright &copy; 2019 José Lorente Martín <jose.lorente.martin@gmail.com>.

Licensed under the BSD 3-Clause License. See LICENSE.txt for details.
