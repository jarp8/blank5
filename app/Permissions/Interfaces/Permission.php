<?php

namespace App\Permissions\Interfaces;

interface Permission
{
    public function allPermissions();
    public function hasPermission($name);
}
