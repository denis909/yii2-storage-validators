<?php

namespace denis909\yii;

use yii\validators\ImageValidator;

class StorageImageValidator extends ImageValidator
{

    use StorageFileValidatorTrait;

}