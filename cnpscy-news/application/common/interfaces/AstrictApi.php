<?php

namespace app\common\interfaces;

interface AstrictApi
{
    public function index(Request $request);

    public function detail(Request $request);

    public function create(Request $request);

    public function update(Request $request);

    public function delete(Request $request);

    public function checkFieldStatus(Request $request);
}