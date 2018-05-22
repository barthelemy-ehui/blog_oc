<?php
namespace App\Controllers;

class HomeController extends Controller
{
    
    /**
     * http_method=get
     */
    public function index(): void
    {
        $latestThreePosts = $this->app->load('repoManager')
            ->getInstance('PostRepository')
            ->getTheTreeLatestPosts();
        
        echo $this->app->load('twig')->render(
            'front/index.twig', [
            'posts' => $latestThreePosts
            ]
        );
    }
}
