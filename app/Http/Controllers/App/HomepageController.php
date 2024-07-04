<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomepageController extends Controller
{

    public function index(Request $request)
    {
        $projectAll = new Collection();
        if(Schema::hasTable('projects')) {
            $projectAll = Project::isPublicated()->forDetail()->get();
        }

        $projects = [
            'Nejnovější projekty' => [
                'selected' => '1',
                'titleCenter' => true,
                'data' => [
                    '1' => $projectAll,
                ],
            ]
        ];

        return view('homepage', [
            'projects' => $projects,
            'projectsListButtonAll' => ['title' => 'Zobrazit vše', 'url' => Route('projects.index')]
        ]);
    }

    public function saveEmail(Request $request)
    {
        DB::table('emails')->insert(['email' => $request->email]);
        return response()->json(['status' => 'ok']);
    }

    public function kontakt()
    {
        return view('app.kontakt');
    }

    public function sitemap()
    {
        $routes = [
            'homepage',
            'jak-to-funguje',
//            'o-nas',
            'kontakt',
            'zasady-zpracovani-osobnich-udaju',
            'vseobecne-obchodni-podminky',
        ];

        $urls = [];
        foreach ($routes as $route) {
            if (is_array($route)) {
                foreach ($route['params'] as $key => $values) {
                    foreach ($values as $value) {
                        $urls[] = ['loc' => route($route['route'], [$key => $value->$key])];
                    }
                }
                continue;
            }
            $urls[] = ['loc' => route($route)];
        };

        foreach (Category::CATEGORIES as $category) {
            $urls[] = ['loc' => route('projects.index.category', ['category' => $category['url']])];
            $subcategories = Category::where('category', $category['id'])->get();
            foreach ($subcategories as $subcategory) {
                $urls[] = ['loc' => route('projects.index.category', ['category' => $category['url'], 'subcategory' => $subcategory['url'],])];
            }

            $projects = Project::where('type', $category['id'])->isPublicated()->get();
            foreach ($projects as $project) {
                $urls[] = ['loc' => $project->url_detail];
            }
        }

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

XML;
        foreach ($urls as $url) {
            $xml .= <<<XML
    <url>
        <loc >{$url['loc']}</loc >
    </url >

XML;
        }
        $xml .= <<<XML
</urlset >
XML;

        return response()->view('sitemap', ['xml' => $xml])->header('Content-type', 'text/xml');
    }
}
