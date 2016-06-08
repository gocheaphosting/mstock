<?php
/*
 * This file is part of the Binidini project.
 *
 * (c) Denis Manilo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

chdir('..');
system('/usr/bin/env -i /usr/bin/git pull origin develop 2>&1');

echo "ok";