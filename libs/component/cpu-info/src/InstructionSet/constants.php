<?php

declare(strict_types=1);

namespace Boson\Component\CpuInfo\InstructionSet;

//
// This "$name" hack removes these constants from IDE autocomplete.
//

define($name = 'Boson\Component\CpuInfo\InstructionSet\MMX', new BuiltinInstructionSet('mmx'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\SSE', new BuiltinInstructionSet('sse'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\SSE2', new BuiltinInstructionSet('sse2'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\SSE3', new BuiltinInstructionSet('sse3'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\SSSE3', new BuiltinInstructionSet('ssse3'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\SSE4_1', new BuiltinInstructionSet('sse4.1'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\SSE4_2', new BuiltinInstructionSet('sse4.2'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\FMA3', new BuiltinInstructionSet('fma3'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\AVX', new BuiltinInstructionSet('avx'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\AVX2', new BuiltinInstructionSet('avx2'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\AVX512F', new BuiltinInstructionSet('avx512f'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\AES', new BuiltinInstructionSet('aes'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\EM64T', new BuiltinInstructionSet('em64t'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\POPCNT', new BuiltinInstructionSet('popcnt'));

define($name = 'Boson\Component\CpuInfo\InstructionSet\F16C', new BuiltinInstructionSet('f16c'));

unset($name);
