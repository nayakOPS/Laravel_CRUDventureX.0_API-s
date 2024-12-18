<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\CategoryIndexRequest;
use App\Http\Filters\CategoryFilter;

class CategoryController extends Controller
{
    public function index(CategoryIndexRequest $request)
    {
        $query = Category::query()->with('projects');
        $filter = new CategoryFilter($request, $query);
        // $categories = $filter->apply()->get();
        $categories = $filter->apply()->paginate(10);

        return CategoryResource::collection($categories);
    }

    // public function index()
    // {
    //     return CategoryResource::collection(Category::with('projects')->get());
    // }

    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create($request->validated());

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category->load('projects'));
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }
}
