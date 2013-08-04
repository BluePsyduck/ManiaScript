<?php

namespace ManiaScript\Event;

/**
 * This class represents the Loop pseudo event.
 *
 * The Loop event is triggered once in every iteration of the event loop, before the events are handled themselves. Be
 * careful of what to add as a Loop event, as this code will get executed quite often.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Loop extends AbstractEvent {

}