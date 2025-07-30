<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\StatusCode\Category;

//
// This "$name" hack removes these constants from IDE autocomplete.
//

define($name = 'Boson\Component\Http\Component\StatusCode\Category\INFORMATIONAL', new HttpStatusCodeCategory('Informational'));

define($name = 'Boson\Component\Http\Component\StatusCode\Category\SUCCESSFUL', new HttpStatusCodeCategory('Successful'));

define($name = 'Boson\Component\Http\Component\StatusCode\Category\REDIRECTION', new HttpStatusCodeCategory('Redirection'));

define($name = 'Boson\Component\Http\Component\StatusCode\Category\CLIENT_ERROR', new HttpStatusCodeCategory('Client Error'));

define($name = 'Boson\Component\Http\Component\StatusCode\Category\SERVER_ERROR', new HttpStatusCodeCategory('Server Error'));

unset($name);
