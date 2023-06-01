<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(){
        $categories = Category::All();
        return $categories;
    }

    public function create(Request $request){
        $request->validate([
            'category_name' => 'required | string | max:255 '
        ]);

        $category = New Category();
        $category->category_name = $request->category_name;
        $category->save();
        return response()->json([
            'message' => 'Category created successfully'
        ]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'category_name' => 'required | string | max:255 '
        ]);

        $category = Category::findOrFail($id);
        $category->category_name = $request->category_name;

        $category->save();
        return response()->json([
            'message' => 'Category updated successfully'
        ]);
    }

    public function delete( $id){
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'message' => 'Category deleted'
        ]);
    }

}
