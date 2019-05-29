<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseSingleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $per_page = empty($request->query('per_page')) ? 10 : (int)$request->query('per_page');
        $courses = Course::with('user')
            ->withCount('lessons')
            ->latest()
            ->get();
//            ->paginate($per_page);

        return CourseResource::collection($courses);
    }

    /**
     * Get all courses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $courses = DB::table('courses')
            ->pluck('title', 'id')
            ->toArray();

        return response()->json($courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'excerpt' => 'required',
            'content' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $request->merge(['user_id' => auth()->id()]);
            // create course
            Course::create($request->all());

            DB::commit();

            return response()->json([
                'message' => Lang::get('crud.create')
            ], 201);
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return response()->json([
                'message' => Lang::get('crud.error')
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Course $course
     * @return CourseSingleResource
     */
    public function show(Course $course)
    {
        Resource::withoutWrapping();
        return new CourseSingleResource($course);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Course $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return response()->json($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Course $course
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, Course $course)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        DB::beginTransaction();
        try {
            // update course
            $course->update($request->all());

            DB::commit();

            return response()->json([
                'message' => Lang::get('crud.update')
            ], 200);
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return response()->json([
                'message' => Lang::get('crud.error')
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Course $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        try {
            // delete course
            $course->delete();

            return response()->json([
                'message' => Lang::get('crud.delete')
            ], 200);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json([
                'message' => Lang::get('crud.error')
            ], 400);
        }
    }
}
