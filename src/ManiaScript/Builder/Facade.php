<?php

namespace ManiaScript\Builder;

use ManiaScript\Builder;
use ManiaScript\Builder\Directive\Constant;
use ManiaScript\Builder\Directive\Library;
use ManiaScript\Builder\Directive\Setting;
use ManiaScript\Builder\Event\Custom;
use ManiaScript\Builder\Event\EntrySubmit;
use ManiaScript\Builder\Event\FirstLoop;
use ManiaScript\Builder\Event\KeyPress;
use ManiaScript\Builder\Event\Load;
use ManiaScript\Builder\Event\Loop;
use ManiaScript\Builder\Event\MenuNavigation;
use ManiaScript\Builder\Event\MouseClick;
use ManiaScript\Builder\Event\MouseOut;
use ManiaScript\Builder\Event\MouseOver;
use ManiaScript\Builder\Event\Timer;

/**
 * A facade for the actual builder to simplify adding new content, if desired.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Facade {
    /**
     * The Builder instance.
     * @var \ManiaScript\Builder
     */
    protected $builder;

    /**
     * Initializes the facade.
     * @param \ManiaScript\Builder $builder The builder instance to be used. If omitted, a new one will be created.
     */
    public function __construct(Builder $builder = null) {
        if (is_null($builder)) {
            $builder = new Builder();
        }
        $this->builder = $builder;
    }

    /**
     * Returns the options of the builder.
     * @return \ManiaScript\Builder\Options The options.
     */
    public function getOptions() {
        return $this->builder->getOptions();
    }

    /**
     * Adds a #Setting directive to the ManiaScript.
     * @param string $name The name of the setting.
     * @param string $value The value of the setting.
     * @return $this Implementing fluent interface.
     */
    public function addSetting($name, $value) {
        $setting = new Setting();
        $setting->setName($name)
                ->setValue($value);
        $this->builder->addDirective($setting);
        return $this;
    }

    /**
     * Adds a #Const directive to the ManiaScript.
     * @param string $name The name of the constant.
     * @param string $value The value of the constant.
     * @return $this Implementing fluent interface.
     */
    public function addConstant($name, $value) {
        $constant = new Constant();
        $constant->setName($name)
                 ->setValue($value);
        $this->builder->addDirective($constant);
        return $this;
    }

    /**
     * Adds a #Include directive to the ManiaScript.
     * @param string $libraryName The name of the library.
     * @param string $alias The alias to be used.
     * @return $this Implementing fluent interface.
     */
    public function addLibrary($libraryName, $alias = '') {
        $library = new Library();
        $library->setLibrary($libraryName)
                ->setAlias($alias);
        $this->builder->addDirective($library);
        return $this;
    }

    /**
     * Adds code to the global scope of the ManiaLink.
     * @param string $code The code.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @return $this Implementing fluent interface.
     */
    public function addGlobalCode($code, $priority = 5) {
        $globalCode = new Code();
        $globalCode->setCode($code)
                   ->setPriority($priority);
        $this->builder->addGlobalCode($globalCode);
        return $this;
    }

    /**
     * Adds a MouseClick event to the ManiaScript.
     * @param string $code The code to be executed.
     * @param array $controlIds The Control IDs by which the code should be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addMouseClick($code, $controlIds = array(), $priority = 5, $inline = false) {
        $mouseClick = new MouseClick();
        $mouseClick->setCode($code)
                   ->setControlIds($controlIds)
                   ->setPriority($priority)
                   ->setInline($inline);
        $this->builder->addEvent($mouseClick);
        return $this;
    }

    /**
     * Adds a MouseOver event to the ManiaScript.
     * @param string $code The code to be executed.
     * @param array $controlIds The Control IDs by which the code should be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addMouseOver($code, $controlIds = array(), $priority = 5, $inline = false) {
        $mouseOver = new MouseOver();
        $mouseOver->setCode($code)
                  ->setControlIds($controlIds)
                  ->setPriority($priority)
                  ->setInline($inline);
        $this->builder->addEvent($mouseOver);
        return $this;
    }


    /**
     * Adds a MouseOut event to the ManiaScript.
     * @param string $code The code to be executed.
     * @param array $controlIds The Control IDs by which the code should be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addMouseOut($code, $controlIds = array(), $priority = 5, $inline = false) {
        $mouseOut = new MouseOut();
        $mouseOut->setCode($code)
                 ->setControlIds($controlIds)
                 ->setPriority($priority)
                 ->setInline($inline);
        $this->builder->addEvent($mouseOut);
        return $this;
    }

    /**
     * Adds a EntrySubmit event to the ManiaScript.
     * @param string $code The code to be executed.
     * @param array $controlIds The Control IDs by which the code should be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addEntrySubmit($code, $controlIds = array(), $priority = 5, $inline = false) {
        $entrySubmit = new EntrySubmit();
        $entrySubmit->setCode($code)
                    ->setControlIds($controlIds)
                    ->setPriority($priority)
                    ->setInline($inline);
        $this->builder->addEvent($entrySubmit);
        return $this;
    }

    /**
     * Adds a KeyPress event to the ManiaScript.
     * @param string $code The code to be executed.
     * @param array $keyCodes The codes of the keys on which the code should be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addKeyPress($code, $keyCodes = array(), $priority = 5, $inline = false) {
        $keyPress = new KeyPress();
        $keyPress->setCode($code)
                 ->setKeyCodes($keyCodes)
                 ->setPriority($priority)
                 ->setInline($inline);
        $this->builder->addEvent($keyPress);
        return $this;
    }

    /**
     * Adds a MenuNavigation event to the ManiaScript.
     * @param string $code The code to be executed.
     * @param array $actions The actions on which the code should be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addMenuNavigation($code, $actions = array(), $priority = 5, $inline = false) {
        $menuNavigation = new MenuNavigation();
        $menuNavigation->setCode($code)
                       ->setActions($actions)
                       ->setPriority($priority)
                       ->setInline($inline);
        $this->builder->addEvent($menuNavigation);
        return $this;
    }

    /**
     * Adds a Load pseudo event to the ManiaScript.
     * @param string $code The code to be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addLoad($code, $priority = 5, $inline = false) {
        $load = new Load();
        $load->setCode($code)
             ->setPriority($priority)
             ->setInline($inline);
        $this->builder->addEvent($load);
        return $this;
    }

    /**
     * Adds a FirstLoop pseudo event to the ManiaScript.
     * @param string $code The code to be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addFirstLoop($code, $priority = 5, $inline = false) {
        $firstLoop = new FirstLoop();
        $firstLoop->setCode($code)
                  ->setPriority($priority)
                  ->setInline($inline);
        $this->builder->addEvent($firstLoop);
        return $this;
    }

    /**
     * Adds a Loop pseudo event to the ManiaScript.
     * @param string $code The code to be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addLoop($code, $priority = 5, $inline = false) {
        $loop = new Loop();
        $loop->setCode($code)
             ->setPriority($priority)
             ->setInline($inline);
        $this->builder->addEvent($loop);
        return $this;
    }

    /**
     * Adds a custom event to the ManiaScript.
     * @param string $name The name of the event.
     * @param string $code The code to be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addCustomEvent($name, $code, $priority = 5, $inline = false) {
        $custom = new Custom();
        $custom->setName($name)
               ->setCode($code)
               ->setPriority($priority)
               ->setInline($inline);
        $this->builder->addEvent($custom);
        return $this;
    }

    /**
     * Returns the ManiaScript code to trigger a custom event.
     * @param string $name The name of the event to trigger.
     * @return string The ManiaScript code. Insert it into any other ManiaScript code.
     */
    public function getTriggerCustomEventCode($name) {
        return $this->builder->getTriggerCustomEventCode($name);
    }

    /**
     * Adds a timer handler to the ManiaScript.
     * @param string $name The name of the timer.
     * @param string $code The code to be executed.
     * @param int $priority The priority, 0 for most important, bigger for less important.
     * @param boolean $inline Whether this event is handled inline, i.e. without wrapping function.
     * @return $this Implementing fluent interface.
     */
    public function addTimer($name, $code, $priority = 5, $inline = false) {
        $timer = new Timer();
        $timer->setName($name)
               ->setCode($code)
               ->setPriority($priority)
               ->setInline($inline);
        $this->builder->addEvent($timer);
        return $this;
    }

    /**
     * Returns the ManiaScript code to add a new timer.
     * @param string $name The name of the timer.
     * @param int $delay The delay of the timer in milliseconds.
     * @param bool $replaceExisting Whether to replace existing timers with the same name.
     * @return string The ManiaScript code. Insert it into any other ManiaScript code.
     */
    public function getAddTimerCode($name, $delay, $replaceExisting = false) {
        return $this->builder->getAddTimerCode($name, $delay, $replaceExisting);
    }

    /**
     * Builds the code and returns the built code.
     * @return string The built code.
     */
    public function build() {
        $this->builder->build();
        return $this->builder->getCode();
    }
}