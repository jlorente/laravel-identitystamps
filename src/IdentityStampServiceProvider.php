<?php

/**
 * Part of the IdentityStamps Laravel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    IdentityStamps Laravel
 * @version    1.0.0
 * @author     Jose Lorente
 * @license    BSD License (3-clause)
 * @copyright  (c) 2018, Jose Lorente
 */

namespace Jlorente\Laravel\IdentityStamp;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

/**
 * Class IdentityStampServiceProvider.
 * 
 * Register the provider in the app config file in order to load the overrided 
 * classes.
 *
 * @author Jose Lorente <jose.lorente.martin@gmail.com>
 */
class IdentityStampServiceProvider extends ServiceProvider
{

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        Blueprint::macro('identityStamps', function() {
            $this->unsignedInteger('created_by')->nullable();
            $this->unsignedInteger('updated_by')->nullable();
        });

        Blueprint::macro('softDeletesIdentityStamps', function() {
            $this->unsignedInteger('deleted_by')->nullable();
        });
    }

}
