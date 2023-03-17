<?php

namespace app\models\releaseControl;


use app\models\bases\BaseModelRepository;

class ReleaseControlRepository extends BaseModelRepository
{
    /**
     * @see BaseModelRepository
     */
    protected static $entityClass = ReleaseControl::class;

    /**
     * @see BaseModelRepository
     */
    protected $tablePrefix = 'rc';

    /**
     * @param  string $key
     * @return bool
     */
    public function isActiveExist($key)
    {
        $query = $this->newQuery()
            ->where([
                'key' => $key,
                'active' => ReleaseControlEnum::RELEASE_CONTROL_ACTIVE_VALUE,
            ]);
            

        return (bool) $query->exists();
    }
}