<?php

namespace ManiaScript\Builder\Event;

/**
 * This class represents a Load pseudo event.
 *
 * The Load event is triggered directly after the ManiaLink has been loaded and ManiaScript execution has been
 * initiated, and will only get triggered once. The difference between Load and FirstLoop is, that if your ManiaLink
 * redirects to another one, the Load event still gets triggered, whereas the FirstLoop will not get triggered on
 * redirection. Technically, Load is triggered before the first call of yield;, FirstLoop is after it.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Load extends AbstractEvent {

}