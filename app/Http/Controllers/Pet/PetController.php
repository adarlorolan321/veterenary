<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Pet\PetListResource;
use App\Models\Pet\Pet;
use App\Http\Requests\Pet\StorePetRequest;
use App\Http\Requests\Pet\UpdatePetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $page = $request->input('page', 1); // default 1
        $perPage = $request->input('perPage', 50); // default 50
        $queryString = $request->input('query', null);
        $sort = explode('.', $request->input('sort', 'id'));
        $order = $request->input('order', 'asc');

        $data = Pet::query()
            ->with(['user'])
            ->leftJoin('users as user', 'pets.user_id', '=', 'user.id')
            ->select('pets.*', 'user.first_name')
            ->where(function ($query) use ($queryString) {
                if ($queryString && $queryString != '') {
                    // filter result
                    $query->where('user.first_name', 'like', '%' . $queryString . '%')
                        ->orWhere('gender', 'like', '%' . $queryString . '%')
                        ->orWhere('type', 'like', '%' . $queryString . '%')
                        ->orWhere('age', 'like', '%' . $queryString . '%')
                        ->orWhere('dob', 'like', '%' . $queryString . '%')
                        ->orWhere('weight', 'like', '%' . $queryString . '%');
                }
            })
            ->when(count($sort) == 1, function ($query) use ($sort, $order) {
                $query->orderBy($sort[0], $order);
            })
            ->paginate($perPage)
            ->withQueryString();

            $userList = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

        $props = [
            'data' => PetListResource::collection($data),
            'params' => $request->all(),
            'userList' => $userList
        ];

        if ($request->wantsJson()) {
            return json_encode($props);
        }

        if(count($data) <= 0 && $page > 1)
        {
            return redirect()->route('pets.index', ['page' => 1]);
        }
        
        return Inertia::render('Pet/Index', $props);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Pet/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request)
    {
        $data = Pet::create($request->validated());
        sleep(1);

        if ($request->wantsJson()) {
            return new PetListResource($data);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = Pet::findOrFail($id);
        if ($request->wantsJson()) {
            return new PetListResource($data);
        }
        return Inertia::render('Admin/Pet/Show', [
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $data = Pet::findOrFail($id);
        if ($request->wantsJson()) {
            return new PetListResource($data);
        }
        return Inertia::render('Admin/Pet/Edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, string $id)
    {
        $data = Pet::findOrFail($id);
        $data->update($request->validated());
        sleep(1);

        if ($request->wantsJson()) {
            return (new PetListResource($data))
                ->response()
                ->setStatusCode(201);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $data = Pet::findOrFail($id);
        $data->delete();
        sleep(1);

        if ($request->wantsJson()) {
            return response(null, 204);
        }
        return redirect()->back();
    }
}
