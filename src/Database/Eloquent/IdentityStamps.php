<?php

namespace Jlorente\Laravel\IdentityStamp\Database\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * Trait IdentityStamps.
 *
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
trait IdentityStamps
{

    /**
     * Method to boot the trait behaviors.
     */
    public static function bootIdentityStamps()
    {
        static::attachIdentityStampsEvents();
    }

    /**
     * Attaches the events needed to make the trait works.
     * 
     * @return void
     */
    protected static function attachIdentityStampsEvents()
    {
        static::creating(function($model) {
            $model->updateIdentityStamps();
        });
        static::updating(function($model) {
            $model->updateIdentityStamps();
        });

        if (array_search(SoftDeletes::class, class_uses_recursive(static::class)) !== false) {
            static::deleted(function($model) {
                $model->touchSoftDeletesIdentityStamps();
            });
        }
    }

    /**
     * Updates the creation and update ids.
     *
     * @return void
     */
    protected function updateIdentityStamps()
    {
        $identity = $this->getIdentityStampValue();

        $updatedBy = $this->getUpdatedByColumn();
        if (!is_null($updatedBy) && !$this->isDirty($updatedBy)) {
            $this->{$updatedBy} = $identity;
        }

        $createdBy = $this->getCreatedByColumn();
        if (!$this->exists && !is_null($createdBy) &&
                !$this->isDirty($createdBy)) {
            $this->{$createdBy} = $identity;
        }
    }

    /**
     * Updates the creation and update ids and stores them in database.
     *
     * @return void
     */
    protected function touchIdentityStamps()
    {
        $this->updateIdentityStamps();

        $createdBy = $this->getCreatedByColumn();
        $updatedBy = $this->getUpdatedByColumn();

        $this->update([
            $createdBy => $this->{$createdBy}
            , $updatedBy => $this->{$updatedBy}
        ]);
    }

    /**
     * Updates the deletion id.
     *
     * @return void
     */
    protected function updateSoftDeletesIdentityStamps()
    {
        if ($this->isForceDeleting()) {
            return;
        }

        $identity = $this->getIdentityStampValue();

        $deletedBy = $this->getDeletedByColumn();
        $this->{$deletedBy} = $identity;
    }

    /**
     * Updates the deletion id and stores it in database.
     *
     * @return void
     */
    protected function touchSoftDeletesIdentityStamps()
    {
        if ($this->isForceDeleting()) {
            return;
        }

        $this->updateSoftDeletesIdentityStamps();

        $deletedBy = $this->getDeletedByColumn();

        $this->update([
            $deletedBy => $this->{$deletedBy}
        ]);
    }

    /**
     * Get the name of the "created by" column.
     *
     * @return string
     */
    public function getCreatedByColumn()
    {
        return defined('static::CREATED_BY') ? static::CREATED_BY : 'created_by';
    }

    /**
     * Get the name of the "updated by" column.
     *
     * @return string
     */
    public function getUpdatedByColumn()
    {
        return defined('static::UPDATED_BY') ? static::UPDATED_BY : 'updated_by';
    }

    /**
     * Get the name of the "deleted by" column.
     *
     * @return string
     */
    public function getDeletedByColumn()
    {
        return defined('static::DELETED_BY') ? static::DELETED_BY : 'deleted_by';
    }

    /**
     * 
     * @return mixed
     */
    public function getIdentityStampValue()
    {
        return Auth::id();
    }

}
