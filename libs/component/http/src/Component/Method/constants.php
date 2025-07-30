<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\Method;

//
// This "$name" hack removes these constants from IDE autocomplete.
//

define($name = 'Boson\Component\Http\Component\Method\GET', new HttpMethod('GET', true, true));

define($name = 'Boson\Component\Http\Component\Method\HEAD', new HttpMethod('HEAD', true, true));

define($name = 'Boson\Component\Http\Component\Method\OPTIONS', new HttpMethod('OPTIONS', true, true));

define($name = 'Boson\Component\Http\Component\Method\TRACE', new HttpMethod('TRACE', true, true));

define($name = 'Boson\Component\Http\Component\Method\PUT', new HttpMethod('PUT', true, false));

define($name = 'Boson\Component\Http\Component\Method\DELETE', new HttpMethod('DELETE', true, false));

define($name = 'Boson\Component\Http\Component\Method\POST', new HttpMethod('POST', false, false));

define($name = 'Boson\Component\Http\Component\Method\PATCH', new HttpMethod('PATCH', false, false));

define($name = 'Boson\Component\Http\Component\Method\CONNECT', new HttpMethod('CONNECT', false, false));

unset($name);
