<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class TestExample extends Testcase
{
    public function true_Or_Not() {
        $this->assertTrue(true);
    }
}
