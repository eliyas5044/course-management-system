<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Resources\CoursePublicSingleResource;
use App\Http\Resources\LessonSingleResource;
use App\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    /**
     * Get courses for home page.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourses()
    {
        $courses = DB::table('courses')
            ->select('id', 'title', 'excerpt', 'slug')
            ->get();

        return response()->json($courses);
    }

    public function getCourseBySlug($slug)
    {
        $course = Course::where('slug', $slug)->first();
        Resource::withoutWrapping();
        return new CoursePublicSingleResource($course);
    }

    public function getLessonBySlug($slug)
    {
        $lesson = Lesson::where('slug', $slug)->first();
        Resource::withoutWrapping();
        return new LessonSingleResource($lesson);
    }
}
