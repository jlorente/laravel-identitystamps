<?php

namespace Jlorente\Laravel\IdentityStamp\Database\Schema;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;

/**
 * Class Blueprint.
 *
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class Blueprint extends BaseBlueprint
{

    /**
     * Add nullable creation and update ids to the table.
     *
     * @return void
     */
    public function identitystamps()
    {
        $this->integer('created_by')->unsigned()->nullable();

        $this->integer('updated_by')->unsigned()->nullable();
    }

    /**
     * Add nullable deletion id to the table to use along with softDeletes.
     *
     * @return void
     */
    public function softDeletesIdentityStamps()
    {
        $this->integer('deleted_by')->unsigned()->nullable();
    }

}
