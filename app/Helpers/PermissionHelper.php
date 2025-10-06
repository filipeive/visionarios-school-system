<?php

if (!function_exists('userCan')) {
    /**
     * Verificar se o usuário atual tem uma permissão específica
     */
    function userCan($permission)
    {
        return auth()->check() && auth()->user()->can($permission);
    }
}

if (!function_exists('userCanAny')) {
    /**
     * Verificar se o usuário atual tem qualquer uma das permissões fornecidas
     */
    function userCanAny($permissions)
    {
        if (!auth()->check()) return false;
        
        foreach ($permissions as $permission) {
            if (auth()->user()->can($permission)) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('userHasRole')) {
    /**
     * Verificar se o usuário atual tem um role específico
     */
    function userHasRole($role)
    {
        return auth()->check() && auth()->user()->hasRole($role);
    }
}

if (!function_exists('canAccessRoute')) {
    /**
     * Verificar se o usuário pode acessar uma rota baseada em permissões
     */
    function canAccessRoute($routeName, $permissions = [])
    {
        if (!auth()->check()) return false;
        
        if (empty($permissions)) return true;
        
        return userCanAny($permissions);
    }
}
