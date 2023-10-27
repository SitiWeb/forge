<?php

namespace App\Http\Controllers;
use App\Models\Repository;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    public function index()
    {
        $repositories = Repository::all();
        return view('repositories.index', compact('repositories'));
    }

    public function create()
    {
        return view('repositories.create');
    }

    public function store($repositoryData)
    {
        
        // Check if the repository ID exists in the database
        $repository = Repository::firstOrCreate(
            ['repo_id' => $repositoryData['id']],
            [
                'last_modified' => $repositoryData['last_modified'],
                'location' => $repositoryData['location'],
            ]
        );

        $repository->last_modified = $repositoryData['last_modified'];
        $repository->location = $repositoryData['location'];

        $repository->save();
        return $repository;

        

        // You now have access to the data in $archives, $encryption, and $repositoryData arrays.
    }
}
