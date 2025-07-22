<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\Architecture;

//
// This "$name" hack removes these constants from IDE autocomplete.
//

define($name = 'Boson\Component\CpuInfo\Architecture\X86', new BuiltinArchitecture('x86'));

define($name = 'Boson\Component\CpuInfo\Architecture\AMD64', new BuiltinArchitecture('amd64', X86));

define($name = 'Boson\Component\CpuInfo\Architecture\ARM', new BuiltinArchitecture('arm'));

define($name = 'Boson\Component\CpuInfo\Architecture\ARM64', new BuiltinArchitecture('aarch64', ARM));

define($name = 'Boson\Component\CpuInfo\Architecture\ITANIUM', new BuiltinArchitecture('ia64'));

define($name = 'Boson\Component\CpuInfo\Architecture\RISCV32', new BuiltinArchitecture('riscv32'));

define($name = 'Boson\Component\CpuInfo\Architecture\RISCV64', new BuiltinArchitecture('riscv64', RISCV32));

define($name = 'Boson\Component\CpuInfo\Architecture\MIPS', new BuiltinArchitecture('mips'));

define($name = 'Boson\Component\CpuInfo\Architecture\MIPS64', new BuiltinArchitecture('mips64', MIPS));

define($name = 'Boson\Component\CpuInfo\Architecture\PPC', new BuiltinArchitecture('ppc'));

define($name = 'Boson\Component\CpuInfo\Architecture\PPC64', new BuiltinArchitecture('ppc64', PPC));

define($name = 'Boson\Component\CpuInfo\Architecture\SPARC', new BuiltinArchitecture('sparc'));

define($name = 'Boson\Component\CpuInfo\Architecture\SPARC64', new BuiltinArchitecture('sparc64', SPARC));

unset($name);
