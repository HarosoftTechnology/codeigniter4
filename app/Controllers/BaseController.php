<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    
    public $noMenu = false;
    public $noBanner = false;
    public string $title = "";
    public string $pageTitle = "";
    public $keywords = "";
    public $description = "";
    /**
     * Default meta tags. Controllers can override any of these.
     */
    protected $metaTags = [
        'title'       => '', // If empty, it will default to config('site-title')
        'description' => 'This is the default description for the website.',
        'keywords'    => 'default, codeigniter4, meta tags'
    ];

    public function __construct()
    {
    }
    
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
        require_once APPPATH . 'Config/Menus.php';

    }

    public function removeMenu($value = false) {
        $this->noMenu = $value;
        return $this;
    }

    public function removeBanner($value = false) {
        $this->noBanner = $value;
        return $this;
    }

    /**
     * Get the view type based on the first URI segment.
     *
     * @return string
     */
    public static function viewType()
    {
        $type = service('uri')->getSegment(1);
        return ($type === "admincp") ? "backend" : "frontend";
    }

    /**
     * Rendering views with a header and footer, so you don’t need to repeat code in every controller
     */
    protected function render($view, $data = [])
    {
        echo view('layouts/header', array_merge(array('title' => $this->title, 'keywords' => $this->keywords, 'description' => $this->description), $data)); // Load Header
        if($this->noMenu == false && $this->viewType() == 'frontend') {
            echo view('layouts/menu');
        }
        echo $view;    // Load Main Content
        echo view('layouts/footer', $data); // Load Footer
    }

    /**
     * Method to render page content
     * @param string $content
     * @return array|string
     */
    public function renderView($content, $data = [])
    {
        echo view('layouts/header', array_merge(array('title' => $this->title, 'keywords' => $this->keywords, 'description' => $this->description), $data)); // Load Header
        if($this->noMenu == false && $this->viewType() == 'fronend') {
            echo view('layouts/menu');
        }
        echo view($content, $data);                     // Load Main Content
        echo view("layouts/footer", $data); // Load Footer
    }

    /**
     * function to set the current page title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title = ""): static
    {
        $this->pageTitle = $title;
        $siteName = config('App')->siteName ?? 'Harosoft';
        // $this->pageHeading = ($this->pageHeading) ? $this->pageHeading : $title; //this is used to get current page heading
        $this->title = "{$siteName} - {$this->pageTitle}";
        return $this;
    }

    /**
     * Static method for rendering meta tags.
     *
     * @return string
     */
    public function renderMetaTags(): string
    {
        $meta_array = $this->metaTags;
        $html       = "\n";

        foreach ($meta_array as $type => $content) {
            if ($type == 'title' && trim($content) == '') {
                $content = config('site-title');
            }
            if ($type == 'description') {
                $html .= trim($content) != ''
                    ? "\n\t" . '<meta name="description" content="' . $content . '" />'
                        . "\n\t" . '<meta name="twitter:description" content="' . $content . '" />'
                        . "\n\t" . '<meta property="og:description" content="' . $content . '" />'
                        . "\n\t" . '<meta itemprop="description" content="' . $content . '" />'
                    : '';
            }
            if ($type == 'keywords') {
                $html .= trim($content) != ''
                    ? "\n\t" . '<meta name="keywords" content="' . $content . '" />'
                    : '';
            }
        }

        $meta_appends = "\n\t" . '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'
            . "\n\t" . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . "\n\t" . '<meta http-equiv="x-ua-compatible" content="ie=edge">';

        return $html . "\t" . $meta_appends . "\n";
    }

}
