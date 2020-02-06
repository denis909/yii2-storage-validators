<?php

namespace denis909\yii;

use Yii;
use yii\web\UploadedFile;

trait StorageFileValidatorTrait
{

    protected function preparePath($path)
    {
        $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);

        $path = ltrim($path, '/');

        if ($path)
        {
            $path = '/' . $path;
        }

        return $path;
    }

    /**
     * @return yii\web\UploadedFile
     */
    public function createUploadedFile(array $value) : UploadedFile
    {
        $file = new UploadedFile;

        $file->name = $value['name'];

        $file->tempName = realpath(Yii::getAlias('@storage/web') . '/source' . $this->preparePath($value['path']));

        if (!$file->name)
        {
            $file->name = pathinfo($file->tempName, PATHINFO_BASENAME);
        }

        $file->type = $value['type'];

        $file->size = $value['size'];

        return $file;
    }

    /**
     * {@inheritdoc}
     */
    protected function validateValue($value)
    {
        if (is_array($value))
        {
            $value = $this->createUploadedFile($value);

            return parent::validateValue($value);
        }

        return parent::validateValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty($value, $trim = false)
    {
        if (is_array($value))
        {
            return $value ? false : true;
        }

        return parent::isEmpty($value, $trim);
    }

    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        $currentValue = $model->$attribute;

        if ($this->maxFiles != 1 || $this->minFiles > 1)
        {
            $rawFiles = $model->$attribute;

            foreach($rawFiles as $key => $value)
            {
                $model->$attribute[$key] = $this->createUploadedFile($value);
            }
        }

        $return = parent::validateAttribute($model, $attribute);

        $model->$attribute = $currentValue;

        return $return;
    }

}