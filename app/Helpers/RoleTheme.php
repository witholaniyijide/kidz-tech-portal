<?php

function themeGradient()
{
    $role = auth()->user()->roles->first()->name ?? 'admin';

    return match ($role) {
        'director' => 'bg-gradient-director',
        'manager' => 'bg-gradient-manager',
        'tutor' => 'bg-gradient-tutor',
        'parent' => 'bg-gradient-parent',
        default => 'bg-gradient-admin',
    };
}
