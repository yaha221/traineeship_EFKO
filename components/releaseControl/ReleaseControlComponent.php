<?php

namespace app\components\releaseControl;

use yii\db\Query;

use app\models\releaseControl\ReleaseControlRepository;

class ReleaseControlComponent
{
    /**
     * @var array
     */
    protected $keyStorage = [];

    /**
     * @var ReleaseControlRepository
     */
    protected $repository;

    public function __construct()
    {
        $this->repository = ReleaseControlRepository::getInstance();
    }

    /**
     * @param  string $key
     * @return void
     */
    protected function fillFromDb($key)
    {
       $this->keyStorage[$key] = $this->repository->isActiveExist($key);
    }
    
    /**
     * @param  string $key
     * @return boolean
     */
    public function isEnabled($key)
    {
        if (! isset($this->keyStorage[$key])) {
            $this->fillFromDb($key);
        }

        return (bool) $this->keyStorage[$key];
    }
}