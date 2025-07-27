<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Component\Scheme;

//
// This "$name" hack removes these constants from IDE autocomplete.
//

define($name = 'Boson\Component\Uri\Component\Scheme\HTTP', new StandardScheme('http', 80));

define($name = 'Boson\Component\Uri\Component\Scheme\HTTPS', new StandardScheme('https', 443));

define($name = 'Boson\Component\Uri\Component\Scheme\DATA', new StandardScheme('data'));

define($name = 'Boson\Component\Uri\Component\Scheme\FILE', new StandardScheme('file'));

define($name = 'Boson\Component\Uri\Component\Scheme\FTP', new StandardScheme('ftp', 21));

define($name = 'Boson\Component\Uri\Component\Scheme\GOPHER', new StandardScheme('gopher', 70));

define($name = 'Boson\Component\Uri\Component\Scheme\WS', new StandardScheme('ws', 80));

define($name = 'Boson\Component\Uri\Component\Scheme\WSS', new StandardScheme('wss', 443));

unset($name);
