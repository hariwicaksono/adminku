<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userPermissions = $session->get('permissions') ?? [];

        // Permission yang wajib dimiliki (dari route middleware)
        $required = $arguments[0] ?? null;

        // Cek jika permission tidak sesuai
        if (!$required || !in_array($required, $userPermissions)) {
            $accept = Services::request()->getHeaderLine('accept');

            if (str_contains($accept, 'application/json')) {
                // Jika request API (misalnya dari axios/fetch)
                return Services::response()
                    ->setJSON([
                        'status' => false,
                        'message' => 'Permission denied',
                    ])
                    ->setStatusCode(403);
            } else {
                // Jika request dari browser biasa (HTML)
                return redirect('restricted', 'refresh');
            }
        }

        // Jika lolos, lanjutkan
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak digunakan
    }
}
