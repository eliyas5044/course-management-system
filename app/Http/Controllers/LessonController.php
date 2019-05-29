<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Http\Resources\LessonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class LessonController extends Controller
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
        $lessons = Lesson::with('course')
            ->latest()
            ->get();
//            ->paginate($per_page);

        return LessonResource::collection($lessons);
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
            'course_id' => 'required',
            'excerpt' => 'required',
            'content' => 'required'
        ]);
        DB::beginTransaction();
        try {
            // create lesson
            Lesson::create($request->all());

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
     * @param \App\Lesson $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        return response()->json($lesson);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Lesson $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        return response()->json($lesson);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Lesson $lesson
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, Lesson $lesson)
    {
        $this->validate($request, [
            'title' => 'required',
            'course_id' => 'required',
            'excerpt' => 'required',
            'content' => 'required'
        ]);
        DB::beginTransaction();
        try {
            // update lesson
            $lesson->update($request->all());

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
     * @param \App\Lesson $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        try {
            // delete lesson
            $lesson->delete();

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
