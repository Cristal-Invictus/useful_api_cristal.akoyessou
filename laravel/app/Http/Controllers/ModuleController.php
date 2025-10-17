<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;




class ModuleController extends Controller
{
    //
    public function index()
    {
        return response()->json(Module::all());
    }

    public function activate(Request $request, $id)
    {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        UserModule::updateOrCreate(
            ['user_id' => $request->user()->id, 'module_id' => $id],
            ['active' => true]
        );

        return response()->json(['message' => 'Module activated']);
    }

    public function deactivate(Request $request, $id)
    {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        UserModule::updateOrCreate(
            ['user_id' => $request->user()->id, 'module_id' => $id],
            ['active' => false]
        );

        return response()->json(['message' => 'Module deactivated']);
    }
}

