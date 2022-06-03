<?php

namespace Vyui\Services\View;

trait HasViewManager
{
    /**
     * The view manager that belongs to an entity.
     *
     * @var ViewManager
     */
    protected ViewManager $manager;

    /**
     * Set the View Manager that belongs to an Engine.
     *
     * @param ViewManager $manager
     * @return $this
     */
    public function setViewManager(ViewManager $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get the View Manager that belongs to an Engine.
     *
     * @return ViewManager
     */
    public function getViewManager(): ViewManager
    {
        return $this->manager;
    }
}