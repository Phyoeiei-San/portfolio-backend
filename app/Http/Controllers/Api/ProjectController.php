<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     return ProjectResource::collection(Project::latest()->paginate(10));
    // }
    public function index(Request $request)
    {
        $query = Project::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }
        $projects = $query->latest()->get();
        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        if (isset($validated['tech_stack'])) {
            $validated['tech_stack'] = json_encode($validated['tech_stack']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $project = Project::create($validated);

        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Project::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image
            if (
                $project->image &&
                Storage::disk('public')->exists($project->image)
            ) {
                Storage::disk('public')->delete($project->image);
            }
            // Upload new image
            $validated['image'] = $request
                ->file('image')
                ->store('projects', 'public');
        }
        $project->update($validated);
        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if (
            $project->image &&
            Storage::disk('public')->exists($project->image)
        ) {

            Storage::disk('public')->delete($project->image);
        }
        $project->delete();
        return response()->json([
            'message' => 'Project deleted successfully.'
        ]);
    }
}
