<?php

namespace App\Http\Controllers\Medicalrecord;

use App\Http\Controllers\Controller;
use App\Http\Resources\Medicalrecord\MedicalRecordListResource;
use App\Models\Medicalrecord\MedicalRecord;
use App\Http\Requests\Medicalrecord\StoreMedicalRecordRequest;
use App\Http\Requests\Medicalrecord\UpdateMedicalRecordRequest;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MedicalRecordController extends Controller
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

        $data = MedicalRecord::query()
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
            'data' => MedicalRecordListResource::collection($data),
            'params' => $request->all(),
        ];

        if ($request->wantsJson()) {
            return json_encode($props);
        }

        if(count($data) <= 0 && $page > 1)
        {
            return redirect()->route('medical_records.index', ['page' => 1]);
        }

        return Inertia::render('Admin/MedicalRecord', $props);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/MedicalRecord/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicalRecordRequest $request)
    {
        $data = MedicalRecord::create($request->validated());
        sleep(1);

        if ($request->wantsJson()) {
            return new MedicalRecordListResource($data);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = MedicalRecord::findOrFail($id);
        if ($request->wantsJson()) {
            return new MedicalRecordListResource($data);
        }
        return Inertia::render('Admin/MedicalRecord/Show', [
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $data = MedicalRecord::findOrFail($id);
        if ($request->wantsJson()) {
            return new MedicalRecordListResource($data);
        }
        return Inertia::render('Admin/MedicalRecord/Edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicalRecordRequest $request, string $id)
    {
        $data = MedicalRecord::findOrFail($id);
        $data->update($request->validated());
        sleep(1);

        if ($request->wantsJson()) {
            return (new MedicalRecordListResource($data))
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
        $data = MedicalRecord::findOrFail($id);
        $data->delete();
        sleep(1);

        if ($request->wantsJson()) {
            return response(null, 204);
        }
        return redirect()->back();
    }
}
