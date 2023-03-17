<?php

namespace app\models\releaseControl;

use app\models\bases\BaseModelService;
use Jenssegers\Date\Date;

class ReleaseControlService extends BaseModelService
{
    /**
     * @see BaseModelService
     */
    protected static $repositoryClass = ReleaseControlRepository::class;

    /**
     * @return Date
     */
    protected function printUpdatedAt()
    {
        return Date::parse(
            $this->printAttribute('updated_at')
        );
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return [
            ReleaseControlEnum::RELEASE_CONTROL_ACTIVE_VALUE => ReleaseControlEnum::RELEASE_CONTROL_ACTIVE,
            ReleaseControlEnum::RELEASE_CONTROL_INACTIVE_VALUE => ReleaseControlEnum::RELEASE_CONTROL_INACTIVE,
        ];
    }

    /**
     * @param string|null $format
     * @return string
     */
    public function printUpdatedAtFormat($format = 'd.m.Y H:i:s')
    {
        if (! $this->printAttribute('updated_at', '')) {
            return null;
        }

        return $this->printUpdatedAt()
            ->format($format);
    }
}