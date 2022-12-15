<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Validator;
use App\Imports\ExcelImport;

class UploadController extends Controller
{
    public function index(Request $request){
        try{
            $file = $request->file('file');

            // check file ext
            $ext = $file->getClientOriginalExtension();
            $exts = [
                'xls',
                'xlsx'
            ];
            if(!in_array($ext, $exts)) throw new \Exception('Unsupported file format', 400);

            // get data with index header
            $data = (Excel::toArray(new ExcelImport(), $file))[0];

            // get raw header
            $getData = (Excel::toArray([], $file))[0];
            $header = $getData[0];

            // custom validation, check any space
            Validator::extend('without_spaces', function($attr, $value){
                return preg_match('/^\S*$/u', $value);
            });

            $rules = [];
            // set custom messages
            $customMessages = [
                'required'          => 'Missing value in :attribute',
                'without_spaces'    => ':attribute should not contain any space',
            ];

            // set custom rules
            foreach($header as $v){
                $index = strtolower(str_replace(['#','*'],'',$v));
                $rules[$index] = substr($v, -1) == '*' ? 'required' : 'nullable';
                if(substr($v, 0, 1) == '#') $rules[$index] .= '|without_spaces';
            }

            // set custom attributes
            $keys = array_keys($rules);
            $customAttributes = array_combine($keys, $keys);

            $error = [];
            // check data from excel
            foreach($data as $i => $v){
                $error_message = null;
                $rowNumber = $i + 2;

                $validator = Validator::make($v, $rules, $customMessages, $customAttributes);
                $error_message = $validator->errors()->toArray();
                if($error_message) $error["$rowNumber"] = $error_message;
            }

            if($error){
                return response()->json([
                    "error"     => $error,
                ], 400);
            }
        }catch(\Exception $e){

            return response()->json([
                'error' => $e->getMessage()
            ], $this->getStatusCode());
        }

        return response()->json([
            "message"   => "success",
        ], 200);
    }
}
