<?php

use Nette\Utils\Random;

define('_token', Random::generate(120, '0-9a-z.A-Z'));
