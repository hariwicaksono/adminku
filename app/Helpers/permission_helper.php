<?php

if (!function_exists('userHasPermission')) {
    function userHasPermission($permission)
    {
        $session = session();

        $permissions = $session->get('permissions');
        if (!is_array($permissions)) {
            return false;
        }

        return in_array($permission, $permissions);

        /* $userId = $session->get('user_id');

        if (!$userId) {
            return false;
        }

        // Cek apakah sudah disimpan di session
        if ($session->has('permissions')) {
            $permissions = $session->get('permissions');
            return in_array($permission, $permissions);
        }

        // Ambil dari database jika belum ada di session
        $db = \Config\Database::connect();
        $builder = $db->table('role_user ru');
        $builder->join('permission_role pr', 'ru.role_id = pr.role_id');
        $builder->join('permissions p', 'pr.permission_id = p.permission_id');
        $builder->where('ru.user_id', $userId);
        $builder->select('p.name');
        $results = $builder->get()->getResultArray();

        $permissionNames = array_column($results, 'name');
        $session->set('permissions', $permissionNames); // Cache ke session

        return in_array($permission, $permissionNames); */
    }
}

