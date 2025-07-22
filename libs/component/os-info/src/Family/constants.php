<?php

declare(strict_types=1);

namespace Boson\Component\OsInfo\Family;

//
// This "$name" hack removes these constants from IDE autocomplete.
//

define($name = 'Boson\Component\OsInfo\Family\WINDOWS', new BuiltinFamily('Windows'));

define($name = 'Boson\Component\OsInfo\Family\UNIX', new BuiltinFamily('Unix'));

define($name = 'Boson\Component\OsInfo\Family\LINUX', new BuiltinFamily('Linux', UNIX));

define($name = 'Boson\Component\OsInfo\Family\BSD', new BuiltinFamily('BSD', UNIX));

define($name = 'Boson\Component\OsInfo\Family\SOLARIS', new BuiltinFamily('Solaris', BSD));

define($name = 'Boson\Component\OsInfo\Family\DARWIN', new BuiltinFamily('Darwin', BSD));

unset($name);
