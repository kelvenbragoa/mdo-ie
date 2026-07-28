<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criticaly;
use App\Models\Equipment;
use App\Models\EquipmentComponent;
use App\Models\EquipmentStatus;
use App\Models\EquipmentSubComponent;
use Illuminate\Http\Request;

class EquipmentComponentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $equipmentcomponents = EquipmentComponent::where('equipment_id',$data['equipment_id'])->sum('percentage_weigth');

        $percentage = $data['percentage_weigth'];
        
        if( $equipmentcomponents+$percentage  > 100){
            return response()->json([
                'message' => 'Não foi possivel adicionar este componente porque excede a percentagem de 100%',
            ], 404);
           
        }
       



        
        $equipment_component = EquipmentComponent::create([
            'name'=>$data['name'],
            'ref'=>$data['ref'],
            'criticaly_id'=>$data['criticaly_id'],
            'equipment_id'=>$data['equipment_id'],
            'equipment_status_id'=>$data['equipment_status_id'],
            'percentage_weigth'=>$data['percentage_weigth'],
            'model'=>$data['model'],
            'make'=>$data['make'],
            'serial'=>$data['serial'],
            
        ]);

        $equipment = Equipment::with('destination')
        ->with('area')
        ->with('supplier')
        ->with('type_equipment')
        ->with('equipment_status')
        ->with('criticaly')
        ->with('acquisition')
        ->with('center_cost')
        ->with('center_cost_account')
        ->find($data['equipment_id']);

        $components = EquipmentComponent::query()
        ->when(request('query'),function($query,$searchQuery){
            $query->where('name','like',"%{$searchQuery}%");
        })
        ->with('equipmentstatus')
        ->with('criticality')
        ->where('equipment_id',$data['equipment_id'])
        ->orderBy('name','asc')
        ->paginate();

        $criticals = Criticaly::orderBy('name','asc')->get();
        $equipmentstatuses = EquipmentStatus::orderBy('name','asc')->get();

        return [

            'equipment'=>$equipment,
            'components'=>$components,
            'criticals'=>$criticals,
            'equipmentstatuses'=>$equipmentstatuses
            
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $component = EquipmentComponent::with('equipmentstatus')->with('criticality')->find($id);

        $subcomponents = EquipmentSubComponent::query()
        ->when(request('query'),function($query,$searchQuery){
            $query->where('name','like',"%{$searchQuery}%");
        })
        ->with('criticality')
        ->with('equipmentstatus')
        ->where('equipment_component_id',$id)
        ->paginate();


        $criticals = Criticaly::get();

        $equipmentstatuses = EquipmentStatus::get();
        
        return [
            'component'=>$component,
            'subcomponents'=>$subcomponents,
            'criticals'=>$criticals,
            'equipmentstatuses'=>$equipmentstatuses
        ];

    }   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $component = EquipmentComponent::with('equipmentstatus')->with('criticality')->find($id);
        $criticals = Criticaly::orderBy('name','asc')->get();
        $equipmentstatuses = EquipmentStatus::orderBy('name','asc')->get();

        return [
            'component'=>$component,
            'criticals'=>$criticals,
            'equipmentstatuses'=>$equipmentstatuses
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $component = EquipmentComponent::find($id);

        $data = $request->all();

        $equipmentcomponents = EquipmentComponent::where('equipment_id',$component->equipment_id)->sum('percentage_weigth');

        $percentage = $data['percentage_weigth'];

        $equipmentcomponents = $equipmentcomponents - $component->percentage_weigth;

        if( $equipmentcomponents+$percentage  > 100){
            return response()->json([
                'message' => 'Não foi possivel editar este componente porque excede a percentagem de 100%',
            ], 404);
           
        }

        $component->update($data);
        return $component;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        // $component = EquipmentComponent::find($id);
        // $component->delete();
        return response()->noContent();
    }
}
