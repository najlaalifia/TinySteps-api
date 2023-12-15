<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    public function show($id);
    public function showAll();
    public function delete($id);

    public function update($id, array $data);
    public function store(array $data);
}
