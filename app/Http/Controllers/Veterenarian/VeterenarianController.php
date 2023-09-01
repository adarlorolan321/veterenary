<?php

namespace App\Http\Controllers\Veterenarian;

use App\Http\Controllers\Controller;
use App\Http\Resources\Veterenarian\VeterenarianListResource;
use App\Models\Veterenarian\Veterenarian;
use App\Http\Requests\Veterenarian\StoreVeterenarianRequest;
use App\Http\Requests\Veterenarian\UpdateVeterenarianRequest;

use Illuminate\Http\Request;
use Inertia\Inertia;

class VeterenarianController extends Controller
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

        $data = Veterenarian::query()
            ->with([])
            ->where(function ($query) use ($queryString) {
                if ($queryString && $queryString != '') {
                    // filter result
                    // $query->where('column', 'like', '%' . $queryString . '%')
                    //     ->orWhere('column', 'like', '%' . $queryString . '%');
                }
            })
            ->when(count($sort) == 1, function ($query) use ($sort, $order) {
                $query->orderBy($sort[0], $order);
            })
            ->paginate($perPage)
            ->withQueryString();

        $props = [
            'data' => VeterenarianListResource::collection($data),
            'params' => $request->all(),
        ];

        if ($request->wantsJson()) {
            return json_encode($props);
        }

        if(count($data) <= 0 && $page > 1)
        {
            return redirect()->route('veterenarians.index', ['page' => 1]);
        }

        return Inertia::render('Admin/Veterenarian', $props);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Veterenarian/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVeterenarianRequest $request)
    {
        $data = Veterenarian::create($request->validated());
        sleep(1);

        if ($request->wantsJson()) {
            return new VeterenarianListResource($data);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = Veterenarian::findOrFail($id);
        if ($request->wantsJson()) {
            return new VeterenarianListResource($data);
        }
        return Inertia::render('Admin/Veterenarian/Show', [
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $data = Veterenarian::findOrFail($id);
        if ($request->wantsJson()) {
            return new VeterenarianListResource($data);
        }
        return Inertia::render('Admin/Veterenarian/Edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVeterenarianRequest $request, string $id)
    {
        $data = Veterenarian::findOrFail($id);
        $data->update($request->validated());
        sleep(1);

        if ($request->wantsJson()) {
            return (new VeterenarianListResource($data))
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
        $data = Veterenarian::findOrFail($id);
        $data->delete();
        sleep(1);

        if ($request->wantsJson()) {
            return response(null, 204);
        }
        return redirect()->back();
    }
}
