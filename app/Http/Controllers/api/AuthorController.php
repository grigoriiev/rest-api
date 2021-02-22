<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Http\Resources\AuthorCollection;
use App\Models\Author;

use Illuminate\Http\Request;

/**
 * Class AuthorController
 * @package App\Http\Controllers\api
 */
class AuthorController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){

        $request->validate([
                'name' => 'required|string',
                'surname' => 'required|string|min:3',
                'magazine_id'=>'required|integer'
               ]
        );


        try {
            $magazine = Author::create([
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'patronymic' => $request->input('patronymic'),
                'magazine_id'=>$request->input('author_id')
            ]);
        }catch (\Exception $ex){
            return response()->json(['fail' =>'Add author'],400);
        }

        return response()->json(['add' =>'Add author'],201);

    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id){

        $request->validate([
                'name' => 'required|string',
                'surname' => 'required|string|min:3',
                'magazine_id'=>'required|integer']
        );
        $author=Author::find($id);


        try {
            $author->update([
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'patronymic' => $request->input('patronymic'),
                'magazine_id'=>$request->input('author_id')
            ]);
        }catch (\Exception $ex){
            return response()->json(['fail' =>'Update author'],400);
        }

        return response()->json(['update' =>'Update author'],200);
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id){

        $author=Author::find($id);


        $author->delete();


        return response()->json(['delete' =>'Delete author'],204);
    }


    /**
     * @param Request $request
     * @return AuthorCollection
     */
    public function list(Request $request){



        return  new AuthorCollection((Author::paginate()));


    }
}
