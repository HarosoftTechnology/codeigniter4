<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * This class attaches the refreshed CSRF token to the response headers for every AJAX request
 */
class AjaxCsrfFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // No action needed before the controller runs.
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Manually check if the request header indicates an AJAX request, 
        // then set the header for the new CSRF token.
        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            $response->setHeader('X-CSRF-TOKEN', csrf_hash());
        }
    }
}
