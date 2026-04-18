<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Schema;

trait PreservesUniqueFieldsOnSoftDelete
{
    protected static array $softDeleteUniqueFieldLengths = [];

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

                $model->setAttribute($field, $model->softDeleteUniqueReplacementValue($field, $value));
                $updated = true;
            }

            if ($updated) {
                // Free unique values on trashed rows so replacements can be created normally.
                $model->saveQuietly();
            }
        });
    }

    protected function softDeleteUniqueReplacementValue(string $field, string $value): string
    {
        $suffix = '__deleted__'.base_convert((string) $this->getKey(), 10, 36).base_convert((string) time(), 10, 36);
        $maxLength = $this->softDeleteUniqueFieldMaxLength($field);

        if ($maxLength === null) {
            return $value.$suffix;
        }

        if ($maxLength <= strlen($suffix)) {
            return substr(hash('sha256', $this->getTable().'|'.$field.'|'.$value.'|'.$this->getKey().'|'.time()), 0, $maxLength);
        }

        return substr($value, 0, $maxLength - strlen($suffix)).$suffix;
    }

    protected function softDeleteUniqueFieldMaxLength(string $field): ?int
    {
        $cacheKey = $this->getConnectionName().'|'.$this->getTable().'|'.$field;

        if (array_key_exists($cacheKey, static::$softDeleteUniqueFieldLengths)) {
            return static::$softDeleteUniqueFieldLengths[$cacheKey];
        }

        foreach (Schema::connection($this->getConnectionName())->getColumns($this->getTable()) as $column) {
            if (($column['name'] ?? null) !== $field) {
                continue;
            }

            $type = (string) ($column['type'] ?? '');

            if (preg_match('/\((\d+)\)/', $type, $matches) === 1) {
                return static::$softDeleteUniqueFieldLengths[$cacheKey] = (int) $matches[1];
            }

            return static::$softDeleteUniqueFieldLengths[$cacheKey] = null;
        }

        return static::$softDeleteUniqueFieldLengths[$cacheKey] = null;
    }
}
