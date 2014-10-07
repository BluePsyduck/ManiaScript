<?php

namespace ManiaScript\Builder\Event;

/**
 * This class represents a MenuNavigation event of ManiaScript.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class MenuNavigation extends AbstractEvent {
    /**
     * The focus has been moved upwards.
     * @var string
     */
    const ACTION_UP = 'Up';

    /**
     * The focus has been moved to the right.
     * @var string
     */
    const ACTION_RIGHT = 'Right';

    /**
     * The focus has been moved to the left.
     * @var string
     */
    const ACTION_LEFT = 'Left';

    /**
     * The focus has been moved downwards.
     * @var string
     */
    const ACTION_DOWN = 'Down';

    /**
     * The focused element has been selected.
     * @var string
     */
    const ACTION_SELECT = 'Select';

    /**
     * The focused element has been canceled.
     * @var string
     */
    const ACTION_CANCEL = 'Cancel';

    /**
     * The valid actions.
     * @var array
     */
    protected $validActions = array(
        self::ACTION_UP,
        self::ACTION_RIGHT,
        self::ACTION_LEFT,
        self::ACTION_DOWN,
        self::ACTION_SELECT,
        self::ACTION_CANCEL
    );

    /**
     * The actions this event should listen for.
     * @var array
     */
    protected $actions = array();

    /**
     * Sets the actions this event should listen for.
     * @param array|string $actions Either a single action as string, or multiple actions as array.
     * @return $this Implementing fluent interface.
     */
    public function setActions($actions) {
        if (!is_array($actions)) {
            $actions = array($actions);
        }
        $this->actions = array_filter($actions, array($this, 'isValidAction'));
        return $this;
    }

    /**
     * Returns the actions this event should listen for.
     * @return array The actions.
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * Checks whether the specified action is valid.
     * @param string $action The action to check.
     * @return bool The result of the check.
     */
    protected function isValidAction($action) {
        return in_array($action, $this->validActions);
    }

}