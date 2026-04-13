<?php

namespace App\Models\Concerns;

trait PreservesUniqueFieldsOnSoftDelete
{
    protected static function bootPreservesUniqueFieldsOnSoftDelete(): void
    {
        static::deleted(function ($model): void {
            if (! method_exists($model, 'trashed') || ! $model->trashed() || ! method_exists($model, 'getSoftDeleteUniqueFields')) {
                return;
            }

            $updated = false;

            foreach ($model->getSoftDeleteUniqueFields() as $field) {
                $value = $model->getAttribute($field);

                if (! is_string($value) || $value === '') {
                    continue;
                }

                if (str_contains($value, '__deleted__')) {
                    continue;
                }

                $model->setAttribute($field, $value.'__deleted__'.$model->getKey().'_'.time());
                $updated = true;
            }

            if ($updated) {
                // Free unique values on trashed rows so replacements can be created normally.
                $model->saveQuietly();
            }
        });
    }
}
