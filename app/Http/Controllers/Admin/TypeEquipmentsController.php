<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criticaly;
use App\Models\Equipment;
use App\Models\TypeEquipment;
use App\Models\TypeEquipmentComponent;
use Illuminate\Http\Request;

class TypeEquipmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $searchQuery = request('query');

            $type_equipments = TypeEquipment::query()
            ->when(request('query'),function($query,$searchQuery){
                $query->where('name','like',"%{$searchQuery}%");
            })
            ->with('equipments')
            ->with('available_equipments')
            ->with('unavailable_equipments')
            ->with('imobilized_equipments')
            ->with('components')
            ->orderBy('name','asc')
            ->paginate();

            return $type_equipments;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        $type_equipments = TypeEquipment::create($data);
        return [
            'message'=>'success'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $type_equipment = TypeEquipment::
        with('equipments')
            ->with('available_equipments')
            ->with('unavailable_equipments')
            ->with('imobilized_equipments')
            ->with('components')
        ->find($id);
        $criticals = Criticaly::get();

        $searchQuery = request('query');

        $components = TypeEquipmentComponent::query()
        ->when(request('query'),function($query,$searchQuery){
            $query->where('name','like',"%{$searchQuery}%");
        })
        ->with('criticality')
        ->with('subcomponents')
        ->where('type_equipment_id',$id)
        ->orderBy('name','asc')
        ->paginate();


        return[
            'type_equipment' =>$type_equipment,
            'criticals'=>$criticals,
            'components'=>$components
            ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //

        $type_equipment = TypeEquipment::find($id);
        


        return $type_equipment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = $request->all();


        $type_equipment = TypeEquipment::find($id);

        $type_equipment->update($data);

        return $type_equipment;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $type_equipment = TypeEquipment::find($id);

        $type_equipment->delete();

        return true;
    }


   
}
