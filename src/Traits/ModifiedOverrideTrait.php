<?php declare(strict_types=1);

namespace ShveiderDto\Traits;

trait ModifiedOverrideTrait
{
    public function modifiedToArray(bool $recursive = false): array
    {
        foreach (get_class_vars(static::class) as $name => $defaultValue) {
            if (isset(static::SHARED_SKIPPED_PROPERTIES[$name]) || isset($this->__modified[$name])) {
                continue;
            }

            if (isset($this->$name) && $this->$name !== $defaultValue) {
                $this->modify($name);
            }
        }

        return parent::modifiedToArray($recursive);
    }
}
