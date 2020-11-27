<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
namespace Hoa\Iterator\Test\Unit;

use Hoa\Iterator as LUT;
use Hoa\Test;
/**
 * Class \Hoa\Iterator\Test\Unit\Append.
 *
 * Test suite of the repeater iterator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Append extends \Hoa\Test\Unit\Suite
{
    public function case_classic()
    {
        $this->given($counter1 = new \Hoa\Iterator\Counter(0, 12, 3), $counter2 = new \Hoa\Iterator\Counter(13, 23, 2), $append = new \Hoa\Iterator\Append(), $append->append($counter1), $append->append($counter2))->when($result = \iterator_to_array($append, \false))->then->array($result)->isEqualTo([0, 3, 6, 9, 13, 15, 17, 19, 21]);
    }
    public function case_singleton()
    {
        $this->given($counter1 = new \Hoa\Iterator\Counter(0, 12, 3), $append = new \Hoa\Iterator\Append(), $append->append($counter1))->when($result = \iterator_to_array($append))->then->array($result)->isEqualTo([0, 3, 6, 9]);
    }
    public function case_empty()
    {
        $this->given($append = new \Hoa\Iterator\Append())->when($result = \iterator_to_array($append))->then->array($result)->isEmpty();
    }
}
